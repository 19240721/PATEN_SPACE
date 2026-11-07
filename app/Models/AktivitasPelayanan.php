<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasPelayanan extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_pelayanans';

    protected $fillable = [
        'user_id',
        'nama_petugas',
        'peran',
        'pendaftaran_id',
        'jenis_layanan',
        'aktivitas',
        'deskripsi',
        'status',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    /**
     * Resolusi nama peran pelaksana: 'Operator' atau 'Petugas'
     */
    public static function resolvePeran(?User $user, ?string $fallbackName = null): string
    {
        if ($user) {
            $rawRole = strtolower($user->getRawOriginal('role') ?? '');
            if ($rawRole === 'operator' || stripos($user->name, 'operator') !== false || stripos($user->email, 'operator') !== false) {
                return 'Operator';
            }
            return 'Petugas';
        }

        if ($fallbackName && stripos($fallbackName, 'operator') !== false) {
            return 'Operator';
        }

        return 'Petugas';
    }

    /**
     * Catat aktivitas pelayanan dengan pencegahan pencatatan ganda (deduplikasi 3 detik)
     */
    public static function catat(
        string $aktivitas,
        string $jenisLayanan,
        ?string $deskripsi = null,
        ?Pendaftaran $pendaftaran = null,
        string $status = 'Berhasil',
        ?User $user = null
    ): ?self {
        $user = $user ?? auth()->user();

        if (! $user) {
            return null;
        }

        $rawRole = strtolower($user->getRawOriginal('role') ?? '');
        $role = strtolower($user->role ?? '');

        // Hanya catat aksi dari kelompok operator/petugas (abaikan camat dan masyarakat)
        if (in_array($rawRole, ['masyarakat', 'camat'], true) || in_array($role, ['masyarakat', 'camat'], true)) {
            return null;
        }

        $peran = self::resolvePeran($user);

        // Pencegahan pencatatan ganda dalam rentang 3 detik untuk pendaftaran & aktivitas yang sama
        if ($pendaftaran && static::where('pendaftaran_id', $pendaftaran->id)
            ->where('aktivitas', $aktivitas)
            ->where('created_at', '>=', now()->subSeconds(3))
            ->exists()) {
            return null;
        }

        return static::create([
            'user_id' => $user->id,
            'nama_petugas' => $user->name,
            'peran' => $peran,
            'pendaftaran_id' => $pendaftaran?->id,
            'jenis_layanan' => $jenisLayanan,
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'status' => $status,
            'ip_address' => request()?->ip(),
        ]);
    }

    /**
     * Catat aktivitas fallback bila dipanggil tanpa session user login
     */
    public static function catatFallback(
        string $namaPetugas,
        string $aktivitas,
        string $jenisLayanan,
        ?string $deskripsi = null,
        ?Pendaftaran $pendaftaran = null,
        string $status = 'Berhasil',
        ?string $peran = null
    ): ?self {
        $peran = $peran ?? self::resolvePeran(null, $namaPetugas);

        if ($pendaftaran && static::where('pendaftaran_id', $pendaftaran->id)
            ->where('aktivitas', $aktivitas)
            ->where('created_at', '>=', now()->subSeconds(3))
            ->exists()) {
            return null;
        }

        return static::create([
            'user_id' => null,
            'nama_petugas' => $namaPetugas,
            'peran' => $peran,
            'pendaftaran_id' => $pendaftaran?->id,
            'jenis_layanan' => $jenisLayanan,
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'status' => $status,
            'ip_address' => request()?->ip(),
        ]);
    }
}
