<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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

    public function index()
    {
        return view('register');
    }

    /**
     * Menampilkan form pendaftaran.
     */
    public function create()
    {
        return view('register', ['isCitizen' => false]);
    }

    public function citizenRiwayat(Request $request)
    {
        $query = Pendaftaran::where('user_id', $request->user()->id)
            ->latest('tanggal_daftar')
            ->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('layanan')) {
            $query->where('jenis_layanan', $request->input('layanan'));
        }
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('jenis_layanan', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftarans = $query->get();

        $stats = [
            'total'   => Pendaftaran::where('user_id', $request->user()->id)->count(),
            'baru'    => Pendaftaran::where('user_id', $request->user()->id)->where('status', 'Baru')->count(),
            'proses'  => Pendaftaran::where('user_id', $request->user()->id)->where('status', 'Proses')->count(),
            'selesai' => Pendaftaran::where('user_id', $request->user()->id)->where('status', 'Selesai')->count(),
            'ditolak' => Pendaftaran::where('user_id', $request->user()->id)->where('status', 'Ditolak')->count(),
        ];

        return view('masyarakat.riwayat', compact('pendaftarans', 'stats'));
    }

    public function citizenDashboard(Request $request)
    {
        $pendaftarans = Pendaftaran::where('user_id', $request->user()->id)
            ->latest('tanggal_daftar')
            ->get();

        $stats = [
            'total' => $pendaftarans->count(),
            'baru' => $pendaftarans->where('status', 'Baru')->count(),
            'proses' => $pendaftarans->where('status', 'Proses')->count(),
            'selesai' => $pendaftarans->where('status', 'Selesai')->count(),
            'ditolak' => $pendaftarans->where('status', 'Ditolak')->count(),
        ];

        return view('masyarakat.dashboard', compact('pendaftarans', 'stats'));
    }

    public function citizenCreate(Request $request)
    {
        return view('masyarakat.permohonan', [
            'isCitizen' => true,
            'citizen' => $request->user(),
        ]);
    }

    public function citizenSuccess(Request $request, Pendaftaran $pendaftaran)
    {
        abort_unless($pendaftaran->user_id === $request->user()->id, 404);

        return view('masyarakat.success', compact('pendaftaran'));
    }

    public function dashboard()
    {
        $pendaftarans = Pendaftaran::latest('tanggal_daftar')->latest('id')->get();
        $newCitizenCount = Pendaftaran::where('intake_source', 'masyarakat')->where('status', 'Baru')->count();
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

            return round($x, 2).','.round($y, 2);
        })->implode(' ');
        $newFromCitizen = $pendaftarans
            ->where('intake_source', 'masyarakat')
            ->where('status', 'Baru')
            ->count();

        return view('dashboard-operator', [
            'pendaftarans' => $pendaftarans,
            'services' => $services,
            'newFromCitizen' => $newFromCitizen,
            'total' => $pendaftarans->count(),
            'proses' => $pendaftarans->where('status', 'Proses')->count(),
            'selesai' => $pendaftarans->where('status', 'Selesai')->count(),
            'year' => $year,
            'monthlyCounts' => $monthlyCounts,
            'monthlyMax' => $monthlyMax,
            'chartPoints' => $chartPoints,
            'monthLabels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'newCitizenCount' => $newCitizenCount,
        ]);
    }

    public function store(Request $request)
    {
        $isCitizen = $request->user()->role === 'masyarakat';
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
                Rule::unique('pendaftarans')->where(fn ($query) => $query->where('jenis_layanan', $request->input('jenis_layanan'))),
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
            'jenis_layanan' => ['required', 'string', Rule::in(['PRR', 'KTP', 'KK', 'Akta Kematian', 'Akta Lahir', 'Kedatangan', 'Pindah'])],
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
            'documents' => ['nullable', 'array', 'max:5'],
            'documents.*' => ['file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ]);

        if ($isCitizen) {
            $validated['nik'] = $request->user()->nik;
            $validated['status'] = 'Baru';
            $validated['user_id'] = $request->user()->id;
            $validated['intake_source'] = 'masyarakat';
            $validated['petugas'] = null;
            $validated['tanggal_daftar'] = now()->toDateString();
        } else {
            $validated['status'] = $validated['status'] ?? 'Baru';
            $validated['intake_source'] = 'petugas';
            $validated['petugas'] = $request->user()->name;
        }

        unset($validated['documents']);
        $pendaftaran = Pendaftaran::create($validated);

        $documents = $this->storeDocuments($request, $pendaftaran);
        if ($documents !== []) {
            $pendaftaran->update(['documents' => $documents]);
        }

        if ($isCitizen) {
            return redirect()->route('masyarakat.permohonan.success', $pendaftaran);
        }

        return redirect()->route('pendaftaran.success')->with('success', 'Pendaftaran berhasil disimpan.');
    }

    public function success()
    {
        $pendaftaran = Pendaftaran::latest()->first();

        return view('register-success', compact('pendaftaran'));
    }

    public function document(Request $request, Pendaftaran $pendaftaran, int $index)
    {
        $isStaff = $request->user()->role === 'petugas';
        abort_unless($isStaff || $pendaftaran->user_id === $request->user()->id, 404);

        $document = $pendaftaran->documents[$index] ?? null;
        abort_unless($document && Storage::disk('local')->exists($document['path']), 404);

        return Storage::disk('local')->download(
            $document['path'],
            $document['original_name']
        );
    }

    private function storeDocuments(Request $request, Pendaftaran $pendaftaran): array
    {
        $documents = [];

        foreach ($request->file('documents', []) as $file) {
            $path = $file->store('pendaftaran/'.$pendaftaran->id, 'local');
            if (! $path) {
                throw new \RuntimeException('Dokumen pendaftaran gagal disimpan.');
            }

            $documents[] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ];
        }

        return $documents;
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

        $services = [
            ['type' => 'PRR', 'title' => 'PRR / KTP Baru'],
            ['type' => 'KTP', 'title' => 'KTP'],
            ['type' => 'KK', 'title' => 'Kartu Keluarga'],
            ['type' => 'Akta Lahir', 'title' => 'Akta Kelahiran'],
            ['type' => 'Akta Kematian', 'title' => 'Akta Kematian'],
            ['type' => 'Kedatangan', 'title' => 'Kedatangan'],
            ['type' => 'Pindah', 'title' => 'Pindah'],
        ];
        $newFromCitizen = $pendaftarans
            ->where('intake_source', 'masyarakat')
            ->where('status', 'Baru')
            ->count();

        return view('laporan', compact(
            'pendaftarans',
            'services',
            'newFromCitizen',
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

    public function semuaData(Request $request)
    {
        $services = $this->serviceCatalog();

        $query = Pendaftaran::query()->latest('tanggal_daftar');
        if ($request->filled('q')) {
            $term = $request->string('q')->trim()->toString();
            $query->where(fn ($builder) => $builder
                ->where('nama_lengkap', 'like', "%{$term}%")
                ->orWhere('nik', 'like', "%{$term}%"));
        }
        if ($request->filled('jenis_layanan') && isset($services[$request->input('jenis_layanan')])) {
            $query->where('jenis_layanan', $services[$request->input('jenis_layanan')]['service']);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pendaftarans = $query->get();
        $total = $pendaftarans->count();
        $newFromCitizen = Pendaftaran::where('intake_source', 'masyarakat')
            ->where('status', 'Baru')
            ->count();

        return view('semua-data', compact('pendaftarans', 'services', 'total', 'newFromCitizen'));
    }

    public function layanan(string $jenisLayanan)
    {
        $services = $this->serviceCatalog();
        $columnsByService = [
            'prr' => [
                ['label' => 'Nama', 'field' => 'nama_lengkap'],
                ['label' => 'NIK', 'field' => 'nik'],
                ['label' => 'Tanggal Kedatangan', 'field' => 'tanggal_kedatangan', 'date' => true],
                ['label' => 'Tanggal Daftar', 'field' => 'tanggal_daftar', 'date' => true],
            ],
            'ktp' => [
                ['label' => 'Nama', 'field' => 'nama_lengkap'],
                ['label' => 'NIK', 'field' => 'nik'],
                ['label' => 'Alasan Pembaruan', 'field' => 'alasan_pembaruan'],
                ['label' => 'Tanggal Daftar', 'field' => 'tanggal_daftar', 'date' => true],
            ],
            'kartu-keluarga' => [
                ['label' => 'Nama', 'field' => 'nama_lengkap'],
                ['label' => 'NIK', 'field' => 'nik'],
                ['label' => 'Kepala Keluarga', 'field' => 'nama_kepala_keluarga'],
                ['label' => 'Keterangan', 'field' => 'keterangan_kk'],
                ['label' => 'Tanggal Daftar', 'field' => 'tanggal_daftar', 'date' => true],
            ],
            'akta-kematian' => [
                ['label' => 'Nama Almarhum', 'field' => 'nama_alm'],
                ['label' => 'Nama Pemohon', 'field' => 'nama_lengkap'],
                ['label' => 'NIK', 'field' => 'nik'],
                ['label' => 'Tanggal Meninggal', 'field' => 'tanggal_meninggal', 'date' => true],
            ],
            'akta-kelahiran' => [
                ['label' => 'Nama Anak', 'field' => 'nama_anak_lahir'],
                ['label' => 'Nama Pemohon', 'field' => 'nama_lengkap'],
                ['label' => 'NIK', 'field' => 'nik'],
                ['label' => 'Tanggal Daftar', 'field' => 'tanggal_daftar', 'date' => true],
            ],
            'kedatangan' => [
                ['label' => 'Nama', 'field' => 'nama_kedatangan'],
                ['label' => 'NIK', 'field' => 'nik_kedatangan'],
                ['label' => 'Alamat Asal', 'field' => 'alamat_asal_kedatangan'],
                ['label' => 'Alamat Tujuan', 'field' => 'alamat_tujuan_kedatangan'],
                ['label' => 'Tanggal Kedatangan', 'field' => 'tanggal_kedatangan', 'date' => true],
            ],
            'pindah' => [
                ['label' => 'Nama', 'field' => 'nama_pindah'],
                ['label' => 'NIK', 'field' => 'nik_pindah'],
                ['label' => 'Alamat Asal', 'field' => 'alamat_asal_pindah'],
                ['label' => 'Alamat Tujuan', 'field' => 'alamat_tujuan_pindah'],
                ['label' => 'Alasan', 'field' => 'alasan_pindah'],
            ],
        ];

        abort_unless(isset($services[$jenisLayanan]), 404);

        $serviceTitle = $services[$jenisLayanan]['title'];
        $serviceType = $services[$jenisLayanan]['service'];
        $serviceSlug = $jenisLayanan;
        $columns = $columnsByService[$jenisLayanan];
        $pendaftarans = Pendaftaran::where('jenis_layanan', $serviceType)->latest('tanggal_daftar')->get();

        return view('layanan', compact('services', 'serviceSlug', 'serviceTitle', 'serviceType', 'columns', 'pendaftarans'));
    }

    private function serviceCatalog(): array
    {
        return [
            'prr' => ['title' => 'PRR / KTP Baru', 'service' => 'PRR'],
            'ktp' => ['title' => 'KTP / Pembaharuan', 'service' => 'KTP'],
            'kartu-keluarga' => ['title' => 'Kartu Keluarga', 'service' => 'KK'],
            'akta-kematian' => ['title' => 'Akta Kematian', 'service' => 'Akta Kematian'],
            'akta-kelahiran' => ['title' => 'Akta Kelahiran', 'service' => 'Akta Lahir'],
            'kedatangan' => ['title' => 'Kedatangan', 'service' => 'Kedatangan'],
            'pindah' => ['title' => 'Pindah', 'service' => 'Pindah'],
        ];
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
            'nik' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pendaftarans')
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
            'jenis_layanan' => ['required', 'string', Rule::in(['PRR', 'KTP', 'KK', 'Akta Kematian', 'Akta Lahir', 'Kedatangan', 'Pindah'])],
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
            'documents' => ['nullable', 'array', 'max:5'],
            'documents.*' => ['file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ]);

        $newDocuments = $this->storeDocuments($request, $pendaftaran);
        unset($validated['documents']);
        if ($newDocuments !== []) {
            $validated['documents'] = array_merge($pendaftaran->documents ?? [], $newDocuments);
        }
        $pendaftaran->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    public function destroy(Pendaftaran $pendaftaran)
    {
        $pendaftaran->delete();

        return Redirect::route('dashboard')->with('success', 'Data pendaftaran berhasil dihapus.');
    }

    public function petugasDashboard()
    {
        return $this->dashboard();
    }

    public function permohonanMasuk(Request $request)
    {
        $query = Pendaftaran::where('intake_source', 'masyarakat')->latest('tanggal_daftar');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->input('jenis_layanan'));
        }

        $pendaftarans = $query->get();
        $counts = [
            'baru' => Pendaftaran::where('intake_source', 'masyarakat')->where('status', 'Baru')->count(),
            'proses' => Pendaftaran::where('intake_source', 'masyarakat')->where('status', 'Proses')->count(),
            'selesai' => Pendaftaran::where('intake_source', 'masyarakat')->where('status', 'Selesai')->count(),
            'ditolak' => Pendaftaran::where('intake_source', 'masyarakat')->where('status', 'Ditolak')->count(),
        ];
        $services = collect($this->serviceCatalog())
            ->map(fn (array $service) => [
                'title' => $service['title'],
                'type' => $service['service'],
            ])
            ->values()
            ->all();

        return view('petugas.permohonan-masuk', compact('pendaftarans', 'counts', 'services'));
    }

    public function showPermohonan(Pendaftaran $pendaftaran)
    {
        return view('petugas.detail-permohonan', compact('pendaftaran'));
    }

    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['Baru', 'Proses', 'Selesai', 'Ditolak'])],
            'alasan_penolakan' => ['nullable', 'string', 'max:1000', 'required_if:status,Ditolak'],
            'catatan_petugas' => ['nullable', 'string', 'max:1000'],
        ]);

        $pendaftaran->update([
            'status' => $validated['status'],
            'alasan_penolakan' => $validated['alasan_penolakan'] ?? null,
            'catatan_petugas' => $validated['catatan_petugas'] ?? null,
        ]);

        return redirect()
            ->route('petugas.permohonan.show', $pendaftaran)
            ->with('success', 'Status permohonan berhasil diperbarui menjadi '.$validated['status'].'.');
    }
}
