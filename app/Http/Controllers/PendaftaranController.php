<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

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

    public function create()
    {
        return view('register');
    }

    public function layanan(string $jenisLayanan)
    {
        $services = $this->serviceCatalog();

        abort_unless(isset($services[$jenisLayanan]), 404);

        $service = $services[$jenisLayanan];
        $pendaftarans = Pendaftaran::where('jenis_layanan', $service['type'])
            ->latest('tanggal_daftar')
            ->get();

        return view('layanan', [
            'pendaftarans' => $pendaftarans,
            'serviceSlug' => $jenisLayanan,
            'serviceTitle' => $service['title'],
            'serviceType' => $service['type'],
            'columns' => $service['columns'],
            'services' => $services,
        ]);
    }

    public function laporan(Request $request)
    {
        $services = $this->serviceCatalog();
        $allPendaftarans = Pendaftaran::all();

        foreach ($services as &$service) {
            $service['count'] = $allPendaftarans->where('jenis_layanan', $service['type'])->count();
        }
        unset($service);

        $query = Pendaftaran::query();

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(fn ($builder) => $builder
                ->where('nama_lengkap', 'like', '%' . $search . '%')
                ->orWhere('nik', 'like', '%' . $search . '%'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pendaftarans = $query->latest('tanggal_daftar')->get();

        return view('laporan', [
            'pendaftarans' => $pendaftarans,
            'services' => $services,
            'total' => $allPendaftarans->count(),
        ]);
    }

    public function semuaData(Request $request)
    {
        $services = $this->serviceCatalog();
        $query = Pendaftaran::query();

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(fn ($builder) => $builder
                ->where('nama_lengkap', 'like', '%' . $search . '%')
                ->orWhere('nik', 'like', '%' . $search . '%'));
        }

        if ($request->filled('jenis_layanan') && isset($services[$request->input('jenis_layanan')])) {
            $query->where('jenis_layanan', $services[$request->input('jenis_layanan')]['type']);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('semua-data', [
            'pendaftarans' => $query->latest('tanggal_daftar')->get(),
            'services' => $services,
            'total' => Pendaftaran::count(),
        ]);
    }

    private function serviceCatalog(): array
    {
        return [
            'prr' => [
                'type' => 'PRR',
                'title' => 'PRR / KTP Baru',
                'columns' => [
                    ['label' => 'Bulan', 'field' => 'bulan'],
                    ['label' => 'Minggu Ke', 'field' => 'minggu_ke'],
                    ['label' => 'Tanggal Kedatangan', 'field' => 'tanggal_kedatangan', 'date' => true],
                    ['label' => 'Nama', 'field' => 'nama_lengkap'],
                    ['label' => 'NIK', 'field' => 'nik'],
                    ['label' => 'Tempat Lahir', 'field' => 'tempat_lahir'],
                    ['label' => 'Tanggal Lahir', 'field' => 'tanggal_lahir', 'date' => true],
                    ['label' => 'Jalan', 'field' => 'jalan'],
                    ['label' => 'RT', 'field' => 'rt'],
                    ['label' => 'RW', 'field' => 'rw'],
                    ['label' => 'Desa', 'field' => 'desa'],
                    ['label' => 'No HP', 'field' => 'no_telepon'],
                ],
            ],
            'kartu-keluarga' => [
                'type' => 'KK',
                'title' => 'Kartu Keluarga',
                'columns' => [
                    ['label' => 'Bulan', 'field' => 'bulan'],
                    ['label' => 'Minggu Ke', 'field' => 'minggu_ke'],
                    ['label' => 'Nama', 'field' => 'nama_lengkap'],
                    ['label' => 'NIK', 'field' => 'nik'],
                    ['label' => 'Jalan', 'field' => 'jalan'],
                    ['label' => 'RT', 'field' => 'rt'],
                    ['label' => 'RW', 'field' => 'rw'],
                    ['label' => 'Desa', 'field' => 'desa'],
                    ['label' => 'Keterangan', 'field' => 'keterangan_kk'],
                ],
            ],
            'ktp' => [
                'type' => 'KTP',
                'title' => 'KTP',
                'columns' => [
                    ['label' => 'Nama', 'field' => 'nama_lengkap'],
                    ['label' => 'NIK', 'field' => 'nik'],
                    ['label' => 'Nomor KK', 'field' => 'nomor_kk'],
                    ['label' => 'Jenis Kelamin', 'field' => 'jenis_kelamin'],
                    ['label' => 'Tempat Lahir', 'field' => 'tempat_lahir'],
                    ['label' => 'Tanggal Lahir', 'field' => 'tanggal_lahir', 'date' => true],
                    ['label' => 'Jalan', 'field' => 'jalan'],
                    ['label' => 'RT', 'field' => 'rt'],
                    ['label' => 'RW', 'field' => 'rw'],
                    ['label' => 'Desa', 'field' => 'desa'],
                    ['label' => 'Alasan Pembaruan', 'field' => 'alasan_pembaruan'],
                    ['label' => 'No HP', 'field' => 'no_telepon'],
                ],
            ],
            'akta-kematian' => [
                'type' => 'Akta Kematian',
                'title' => 'Akta Kematian',
                'columns' => [
                    ['label' => 'Bulan', 'field' => 'bulan'],
                    ['label' => 'Minggu Ke', 'field' => 'minggu_ke'],
                    ['label' => 'Nama Almarhum', 'field' => 'nama_alm'],
                    ['label' => 'Nama Ayah', 'field' => 'nama_ayah'],
                    ['label' => 'Nama Ibu', 'field' => 'nama_ibu'],
                    ['label' => 'Tempat Meninggal', 'field' => 'tempat_meninggal'],
                    ['label' => 'Tanggal Meninggal', 'field' => 'tanggal_meninggal', 'date' => true],
                    ['label' => 'Alamat', 'field' => 'alamat'],
                    ['label' => 'Keterangan', 'field' => 'keterangan_kematian'],
                ],
            ],
            'akta-kelahiran' => [
                'type' => 'Akta Lahir',
                'title' => 'Akta Kelahiran',
                'columns' => [
                    ['label' => 'Bulan', 'field' => 'bulan'],
                    ['label' => 'Minggu Ke', 'field' => 'minggu_ke'],
                    ['label' => 'Nama Anak', 'field' => 'nama_anak_lahir'],
                    ['label' => 'Nama Ayah', 'field' => 'nama_ayah_lahir'],
                    ['label' => 'Nama Ibu', 'field' => 'nama_ibu_lahir'],
                    ['label' => 'Berat Badan', 'field' => 'berat_badan'],
                    ['label' => 'Panjang Badan', 'field' => 'panjang_badan'],
                    ['label' => 'Tempat Lahir', 'field' => 'tempat_lahir_detail'],
                    ['label' => 'Alamat', 'field' => 'alamat'],
                ],
            ],
            'kedatangan' => [
                'type' => 'Kedatangan',
                'title' => 'Kedatangan',
                'columns' => [
                    ['label' => 'Bulan', 'field' => 'bulan'],
                    ['label' => 'Minggu Ke', 'field' => 'minggu_ke'],
                    ['label' => 'Tanggal Kedatangan', 'field' => 'tanggal_kedatangan', 'date' => true],
                    ['label' => 'Nama', 'field' => 'nama_kedatangan'],
                    ['label' => 'NIK', 'field' => 'nik_kedatangan'],
                    ['label' => 'Alamat Asal', 'field' => 'alamat_asal_kedatangan'],
                    ['label' => 'Alamat Tujuan', 'field' => 'alamat_tujuan_kedatangan'],
                ],
            ],
            'pindah' => [
                'type' => 'Pindah',
                'title' => 'Pindah',
                'columns' => [
                    ['label' => 'Bulan', 'field' => 'bulan'],
                    ['label' => 'Minggu Ke', 'field' => 'minggu_ke'],
                    ['label' => 'Nama', 'field' => 'nama_pindah'],
                    ['label' => 'NIK', 'field' => 'nik_pindah'],
                    ['label' => 'Alamat Asal', 'field' => 'alamat_asal_pindah'],
                    ['label' => 'Alamat Tujuan', 'field' => 'alamat_tujuan_pindah'],
                    ['label' => 'Alasan Pindah', 'field' => 'alasan_pindah'],
                ],
            ],
        ];
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_lahir' => $this->normalizeManualDate($request->input('tanggal_lahir')),
        ]);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_kepala_keluarga' => ['nullable', 'string', 'max:255'],
            'nik' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pendaftarans', 'nik')->where(fn ($query) => $query->where('jenis_layanan', $request->input('jenis_layanan'))),
            ],
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

        return redirect()->route('pendaftaran.success')->with('success', 'Pendaftaran berhasil disimpan.')->with('pendaftaran_id', $pendaftaran->id);
    }

    public function success()
    {
        $pendaftaran = Pendaftaran::latest()->first();

        return view('register-success', compact('pendaftaran'));
    }

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
            'nik' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pendaftarans', 'nik')
                    ->where(fn ($query) => $query->where('jenis_layanan', $request->input('jenis_layanan')))
                    ->ignore($pendaftaran->id),
            ],
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

        $pendaftaran->update($validated);

        return Redirect::route('dashboard')->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

}
