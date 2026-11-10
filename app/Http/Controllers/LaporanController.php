<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Memfilter data pendaftaran berdasarkan parameter pencarian dan filter.
     */
    private function filterPendaftarans(Request $request)
    {
        $query = Pendaftaran::query()->latest('tanggal_daftar')->latest('id');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('petugas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->input('jenis_layanan'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_daftar', '>=', $request->input('tanggal_awal'));
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_daftar', '<=', $request->input('tanggal_akhir'));
        }

        return $query->get();
    }

    public function index(Request $request)
    {
        $pendaftarans = $this->filterPendaftarans($request);

        $totalPelayanan = $pendaftarans->count();

        $rekapLayanan = $pendaftarans
            ->groupBy('jenis_layanan')
            ->map(fn ($items) => $items->count())
            ->sortDesc();

        $proses = $pendaftarans->where('status', 'Proses')->count();
        $selesai = $pendaftarans->where('status', 'Selesai')->count();
        $total = $pendaftarans->count();
        $prr = $pendaftarans->where('jenis_layanan', 'PRR')->count();
        $ktp = $pendaftarans->where('jenis_layanan', 'KTP')->count();
        $kk = $pendaftarans->where('jenis_layanan', 'KK')->count();
        $kartuKuning = $pendaftarans->where('jenis_layanan', 'Kartu Kuning')->count();

        $services = [
            ['type' => 'PRR', 'title' => 'PRR / KTP Baru'],
            ['type' => 'KTP', 'title' => 'KTP'],
            ['type' => 'KK', 'title' => 'Kartu Keluarga'],
            ['type' => 'Akta Lahir', 'title' => 'Akta Kelahiran'],
            ['type' => 'Akta Kematian', 'title' => 'Akta Kematian'],
            ['type' => 'Kedatangan', 'title' => 'Kedatangan'],
            ['type' => 'Pindah', 'title' => 'Pindah'],
        ];

        $newFromCitizen = Pendaftaran::where('intake_source', 'masyarakat')
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

    public function cetak(Request $request)
    {
        $pendaftarans = $this->filterPendaftarans($request);

        $totalPelayanan = $pendaftarans->count();

        $rekapLayanan = $pendaftarans
            ->groupBy('jenis_layanan')
            ->map(fn ($items) => $items->count())
            ->sortDesc();

        $filters = [
            'q' => $request->input('q'),
            'jenis_layanan' => $request->input('jenis_layanan'),
            'status' => $request->input('status'),
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
        ];

        $logoPath = public_path('images/logo-karawang.png');
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : '';

        $data = [
            'pendaftarans' => $pendaftarans,
            'totalPelayanan' => $totalPelayanan,
            'rekapLayanan' => $rekapLayanan,
            'filters' => $filters,
            'logoBase64' => $logoBase64,
        ];

        // Jika dipanggil via export PDF dan Barryvdh\DomPDF terpasang
        if (($request->input('export') === 'pdf' || $request->boolean('pdf')) && class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan-pdf', $data);
            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream('Laporan-PATEN-Jatisari.pdf');
        }

        return view('laporan-pdf', $data);
    }
}
