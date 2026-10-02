<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::latest('tanggal_daftar')->get();

        $totalPelayanan = $pendaftarans->count();

        $rekapLayanan = $pendaftarans
            ->groupBy('jenis_layanan')
            ->map(function ($items) {
                return $items->count();
            })
            ->sortDesc();

        $proses = $pendaftarans
            ->where('status', 'Proses')
            ->count();

        $selesai = $pendaftarans
            ->where('status', 'Selesai')
            ->count();

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

    public function cetak()
    {
        $pendaftarans = Pendaftaran::latest('tanggal_daftar')->get();

        $totalPelayanan = $pendaftarans->count();

        $rekapLayanan = $pendaftarans
            ->groupBy('jenis_layanan')
            ->map(function ($items) {
                return $items->count();
            })
            ->sortDesc();

        $pdf = Pdf::loadView('laporan-pdf', [
            'pendaftarans' => $pendaftarans,
            'totalPelayanan' => $totalPelayanan,
            'rekapLayanan' => $rekapLayanan,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan-PATEN-Jatisari.pdf');
    }
}