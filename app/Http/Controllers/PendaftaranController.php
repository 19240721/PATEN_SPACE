<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
 
class PendaftaranController extends Controller
{
    private function normalizeManualDate(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = trim($value);

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
            return Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
        }

        return $value;
    }

    public function index()
    {
        return view('register');
    }

    /**
     * Menampilkan form pendaftaran.
     */
    public function create()
    {
        return view('register');
    }

    public function dashboard()
    {
        $pendaftarans = Pendaftaran::latest('tanggal_daftar')->get();
        $services = [
            ['type' => 'PRR', 'title' => 'PRR / KTP Baru', 'label' => 'PRR', 'icon' => '▤', 'tone' => 'blue'],
            ['type' => 'KTP', 'title' => 'KTP', 'label' => 'KTP', 'icon' => '▣', 'tone' => 'blue'],
            ['type' => 'KK', 'title' => 'Kartu Keluarga', 'label' => 'KK', 'icon' => '♧', 'tone' => 'green'],
            ['type' => 'Akta Lahir', 'title' => 'Akta Kelahiran', 'label' => 'Akta Lahir', 'icon' => '✳', 'tone' => 'gold'],
            ['type' => 'Akta Kematian', 'title' => 'Akta Kematian', 'label' => 'Akta Mati', 'icon' => '▧', 'tone' => 'blue'],
            ['type' => 'Kedatangan', 'title' => 'Kedatangan', 'label' => 'Kedatangan', 'icon' => '⌂', 'tone' => 'green'],
            ['type' => 'Pindah', 'title' => 'Pindah', 'label' => 'Pindah', 'icon' => '→', 'tone' => 'blue'],
        ];

        foreach ($services as &$service) {
            $service['count'] = $pendaftarans->where('jenis_layanan', $service['type'])->count();
        }
        unset($service);

        $year = now()->year;
        $monthlyCounts = collect(range(1, 12))->map(function ($month) use ($pendaftarans, $year) {
            return $pendaftarans->filter(function ($item) use ($month, $year) {
                if (blank($item->tanggal_daftar)) {
                    return false;
                }

                $date = Carbon::parse($item->tanggal_daftar);

                return $date->year === $year && $date->month === $month;
            })->count();
        });
        $monthlyMax = max(1, (int) $monthlyCounts->max());
        $chartPoints = $monthlyCounts->map(function ($count, $index) use ($monthlyMax) {
            $x = 24 + ($index * 432 / 11);
            $y = 128 - ($count / $monthlyMax * 100);

            return round($x, 2) . ',' . round($y, 2);
        })->implode(' ');

        return view('dashboard-operator', [
            'pendaftarans' => $pendaftarans,
            'services' => $services,
            'total' => $pendaftarans->count(),
            'proses' => $pendaftarans->where('status', 'Proses')->count(),
            'selesai' => $pendaftarans->where('status', 'Selesai')->count(),
            'year' => $year,
            'monthlyCounts' => $monthlyCounts,
            'monthlyMax' => $monthlyMax,
            'chartPoints' => $chartPoints,
            'monthLabels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_lahir' => $this->normalizeManualDate($request->input('tanggal_lahir')),
        ]);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_kepala_keluarga' => ['nullable', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:20', 'unique:pendaftarans,nik'],
            'jenis_kelamin' => ['nullable', 'string', 'max:20'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'jalan' => ['nullable', 'string', 'max:255'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'desa' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'jenis_layanan' => ['required', 'string', 'max:100'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
            'petugas' => ['nullable', 'string', 'max:255'],
            'bulan' => ['nullable', 'string', 'max:20'],
            'minggu_ke' => ['nullable', 'string', 'max:20'],
            'tanggal_kedatangan' => ['nullable', 'date'],
            'alamat_asal' => ['nullable', 'string', 'max:255'],
            'alamat_tujuan' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'nomor_kk' => ['nullable', 'string', 'max:50'],
            'nama_kk' => ['nullable', 'string', 'max:255'],
            'dusun' => ['nullable', 'string', 'max:255'],
            'hubungan' => ['nullable', 'string', 'max:100'],
            'kepala_keluarga' => ['nullable', 'string', 'max:255'],
            'jumlah_anggota' => ['nullable', 'string', 'max:20'],
            'nama_anak' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin_kk' => ['nullable', 'string', 'max:10'],
            'nik_anak' => ['nullable', 'string', 'max:30'],
            'keterangan_kk' => ['nullable', 'string', 'max:255'],
            'nama_alm' => ['nullable', 'string', 'max:255'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'tempat_meninggal' => ['nullable', 'string', 'max:255'],
            'tanggal_meninggal' => ['nullable', 'date'],
            'keterangan_kematian' => ['nullable', 'string', 'max:255'],
            'nama_anak_lahir' => ['nullable', 'string', 'max:255'],
            'nama_ayah_lahir' => ['nullable', 'string', 'max:255'],
            'nama_ibu_lahir' => ['nullable', 'string', 'max:255'],
            'berat_badan' => ['nullable', 'string', 'max:50'],
            'panjang_badan' => ['nullable', 'string', 'max:50'],
            'tempat_lahir_detail' => ['nullable', 'string', 'max:255'],
            'nama_kedatangan' => ['nullable', 'string', 'max:255'],
            'nik_kedatangan' => ['nullable', 'string', 'max:30'],
            'alamat_asal_kedatangan' => ['nullable', 'string', 'max:255'],
            'alamat_tujuan_kedatangan' => ['nullable', 'string', 'max:255'],
            'keterangan_kedatangan' => ['nullable', 'string', 'max:255'],
            'nama_pindah' => ['nullable', 'string', 'max:255'],
            'nik_pindah' => ['nullable', 'string', 'max:30'],
            'alamat_asal_pindah' => ['nullable', 'string', 'max:255'],
            'alamat_tujuan_pindah' => ['nullable', 'string', 'max:255'],
            'alasan_pindah' => ['nullable', 'string', 'max:255'],
            'tanggal_daftar' => ['required', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $pendaftaran = Pendaftaran::create($validated);

        return redirect()->route('register.success')->with('success', 'Pendaftaran berhasil disimpan.')->with('pendaftaran_id', $pendaftaran->id);
    }

    public function success()
    {
        $pendaftaran = Pendaftaran::latest()->first();

        return view('register-success', compact('pendaftaran'));
    }

    /**
     * Menampilkan halaman laporan.
     */
    public function laporan()
    {
        // Ambil semua data pendaftaran
        $pendaftarans = Pendaftaran::latest('tanggal_daftar')->get();

        // Total seluruh pelayanan
        $totalPelayanan = $pendaftarans->count();

        // Rekap berdasarkan jenis layanan
        $rekapLayanan = $pendaftarans
            ->groupBy('jenis_layanan')
            ->map(function ($items) {
                return $items->count();
            })
            ->sortDesc();

        // Statistik berdasarkan status
        $proses = $pendaftarans
            ->where('status', 'Proses')
            ->count();

        $selesai = $pendaftarans
            ->where('status', 'Selesai')
            ->count();

        // Statistik jenis layanan
        $total = $pendaftarans->count();

        $prr = $pendaftarans
            ->where('jenis_layanan', 'PRR')
            ->count();

        $ktp = $pendaftarans
            ->where('jenis_layanan', 'KTP')
            ->count();

        $kk = $pendaftarans
            ->where('jenis_layanan', 'KK')
            ->count();

        $kartuKuning = $pendaftarans
            ->where('jenis_layanan', 'Kartu Kuning')
            ->count();

        return view('laporan', compact(
            'pendaftarans',
            'totalPelayanan',
            'rekapLayanan',
            'total',
            'prr',
            'ktp',
            'kk',
            'kartuKuning',
            'proses',
            'selesai'
        ));
    }

    /**
     * Menampilkan form edit data pendaftaran.
     */
    public function edit(Pendaftaran $pendaftaran)
    {
        return view('register', compact('pendaftaran'));
    }

    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        $request->merge([
            'tanggal_lahir' => $this->normalizeManualDate($request->input('tanggal_lahir')),
        ]);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_kepala_keluarga' => ['nullable', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:20', 'unique:pendaftarans,nik,' . $pendaftaran->id],
            'jenis_kelamin' => ['nullable', 'string', 'max:20'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'jalan' => ['nullable', 'string', 'max:255'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'desa' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'jenis_layanan' => ['required', 'string', 'max:100'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
            'petugas' => ['nullable', 'string', 'max:255'],
            'bulan' => ['nullable', 'string', 'max:20'],
            'minggu_ke' => ['nullable', 'string', 'max:20'],
            'tanggal_kedatangan' => ['nullable', 'date'],
            'alamat_asal' => ['nullable', 'string', 'max:255'],
            'alamat_tujuan' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'nomor_kk' => ['nullable', 'string', 'max:50'],
            'nama_kk' => ['nullable', 'string', 'max:255'],
            'dusun' => ['nullable', 'string', 'max:255'],
            'hubungan' => ['nullable', 'string', 'max:100'],
            'kepala_keluarga' => ['nullable', 'string', 'max:255'],
            'jumlah_anggota' => ['nullable', 'string', 'max:20'],
            'nama_anak' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin_kk' => ['nullable', 'string', 'max:10'],
            'nik_anak' => ['nullable', 'string', 'max:30'],
            'keterangan_kk' => ['nullable', 'string', 'max:255'],
            'nama_alm' => ['nullable', 'string', 'max:255'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'tempat_meninggal' => ['nullable', 'string', 'max:255'],
            'tanggal_meninggal' => ['nullable', 'date'],
            'keterangan_kematian' => ['nullable', 'string', 'max:255'],
            'nama_anak_lahir' => ['nullable', 'string', 'max:255'],
            'nama_ayah_lahir' => ['nullable', 'string', 'max:255'],
            'nama_ibu_lahir' => ['nullable', 'string', 'max:255'],
            'berat_badan' => ['nullable', 'string', 'max:50'],
            'panjang_badan' => ['nullable', 'string', 'max:50'],
            'tempat_lahir_detail' => ['nullable', 'string', 'max:255'],
            'nama_kedatangan' => ['nullable', 'string', 'max:255'],
            'nik_kedatangan' => ['nullable', 'string', 'max:30'],
            'alamat_asal_kedatangan' => ['nullable', 'string', 'max:255'],
            'alamat_tujuan_kedatangan' => ['nullable', 'string', 'max:255'],
            'keterangan_kedatangan' => ['nullable', 'string', 'max:255'],
            'nama_pindah' => ['nullable', 'string', 'max:255'],
            'nik_pindah' => ['nullable', 'string', 'max:30'],
            'alamat_asal_pindah' => ['nullable', 'string', 'max:255'],
            'alamat_tujuan_pindah' => ['nullable', 'string', 'max:255'],
            'alasan_pindah' => ['nullable', 'string', 'max:255'],
            'alasan_permohonan' => ['nullable', 'string', 'max:255'],
            'alasan_pembaruan' => ['nullable', 'string', 'max:255'],
            'tanggal_daftar' => ['required', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        return redirect()
            ->route('laporan')
            ->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    public function destroy(Pendaftaran $pendaftaran)
    {
        $pendaftaran->delete();

        return Redirect::route('dashboard')->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}
