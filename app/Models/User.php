<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nik',
        'nip',
        'phone',
        'alamat_kantor',
        'foto_profil',
        'email',
        'password',
        'terms_accepted',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'terms_accepted' => 'boolean',
        ];
    }

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => in_array($value, ['operator', 'admin'], true) ? 'petugas' : $value,
        );
    }

    public function getPeranLayananAttribute(): ?string
    {
        $rawRole = strtolower($this->getRawOriginal('role') ?? '');
        $role = strtolower($this->role ?? '');

        if (in_array($rawRole, ['camat', 'masyarakat'], true) || in_array($role, ['camat', 'masyarakat'], true)) {
            return null;
        }

        if ($rawRole === 'operator' || stripos($this->name, 'operator') !== false || stripos($this->email, 'operator') !== false) {
            return 'Operator';
        }

        return 'Petugas';
    }

    public function getJabatanAttribute(): string
    {
        if ($this->role === 'camat') {
            return 'Pimpinan / Camat';
        }

        if ($this->role === 'masyarakat') {
            return 'Masyarakat (Warga Pemohon)';
        }

        if ($this->peran_layanan === 'Operator') {
            return 'Operator Kecamatan';
        }

        return 'Petugas Loket';
    }

    public function getFotoProfilUrlAttribute(): ?string
    {
        if (!empty($this->foto_profil) && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto_profil)) {
            return asset('storage/' . $this->foto_profil);
        }
        return null;
    }

    public function getInisialAttribute(): string
    {
        $words = preg_split('/[\s,\.]+/', trim($this->name ?? ''), -1, PREG_SPLIT_NO_EMPTY);
        if (empty($words)) {
            return strtoupper(substr($this->role ?? 'U', 0, 1));
        }
        if (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 2));
        }
        return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    }
}
