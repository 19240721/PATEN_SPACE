{{-- =========================================================================
   TEMPLATE DOKUMEN RESMI REKAPITULASI LAPORAN PELAYANAN PATEN
   KECAMATAN JATISARI, KABUPATEN KARAWANG
   STANDAR FORMAL ADMINISTRASI PEMERINTAHAN (GAYA MICROSOFT WORD, FONT ARIAL)
   DIGUNAKAN BERSAMA OLEH: PRATINJAU, CETAK LAPORAN, DAN EXPORT PDF
   ========================================================================= --}}

{{-- 1. KOP SURAT RESMI DENGAN LOGO KABUPATEN KARAWANG --}}
<div class="kop-wrapper" style="display: flex; align-items: center; justify-content: center; gap: 18px; margin-bottom: 6px;">
    @php
        $logoUrl = isset($isPdf) && $isPdf && !empty($logoBase64)
            ? $logoBase64
            : asset('images/logo-karawang.png');
    @endphp
    <img src="{{ $logoUrl }}" alt="Logo Kabupaten Karawang" class="kop-logo" style="width: 75px; height: auto; max-height: 90px; object-fit: contain;">
    <div class="kop-text" style="text-align: center;">
        <div class="kop-instansi" style="font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; color: #000000;">PEMERINTAH KABUPATEN KARAWANG</div>
        <div class="kop-kecamatan" style="font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 2px 0; color: #000000;">KECAMATAN JATISARI</div>
        <div class="kop-alamat" style="font-size: 9.5pt; margin: 0; color: #000000; line-height: 1.3;">Jl. Raya Jatisari No. 1, Jatisari, Kec. Jatisari, Kabupaten Karawang, Jawa Barat 41374</div>
    </div>
</div>
<hr class="kop-line-thick" style="border: 0; border-top: 2.5px solid #000000; margin-top: 10px; margin-bottom: 2px;">
<hr class="kop-line-thin" style="border: 0; border-top: 1px solid #000000; margin-top: 0; margin-bottom: 12px;">

{{-- TANGGAL CETAK DI BAGIAN ATAS POJOK KANAN (SEBELUM JUDUL LAPORAN) --}}
<div class="report-date-right" style="text-align: right; font-size: 10pt; color: #000000; margin-bottom: 10px;">
    Karawang, {{ $tanggalCetak }}
</div>

{{-- 2. JUDUL DAN INFORMASI PERIODE LAPORAN --}}
<div class="report-title-block" style="text-align: center; margin-bottom: 14px;">
    <div class="report-title" style="font-size: 13pt; font-weight: bold; text-transform: uppercase; margin: 0 0 5px; color: #000000; letter-spacing: 0.5px;">
        REKAPITULASI LAPORAN PELAYANAN PATEN
    </div>
    <div class="report-periode-info" style="font-size: 10pt; color: #000000; margin: 0;">
        @php
            $periodeText = 'Januari – Desember ' . $tahun;
            if ($bulan !== 'semua' && !empty($bulan)) {
                $periodeText = 'Bulan ' . ucfirst($bulan) . ' Tahun ' . $tahun;
            }
        @endphp
        <strong>Periode:</strong> {{ $periodeText }}
        @if ($jenisLayanan !== 'semua')
            &bull; <strong>Jenis Pelayanan:</strong> {{ $selectedLayananLabel }}
        @endif
        @if ($status !== 'semua')
            &bull; <strong>Status:</strong> {{ $status === 'selesai' ? 'Selesai' : 'Dalam Proses' }}
        @endif
    </div>
</div>

{{-- 3. BAGIAN A — RINGKASAN HASIL PELAYANAN (DUA KOLOM BERDAMPINGAN) --}}
<div class="section-heading" style="font-size: 11pt; font-weight: bold; color: #000000; margin-top: 14px; margin-bottom: 8px; text-transform: uppercase;">
    A. RINGKASAN HASIL PELAYANAN
</div>

@php
    $persenTuntas = $total > 0 ? round(($totalSelesai / $total) * 100, 1) : 0;
@endphp

<table class="summary-side-by-side" style="width: 100%; border-collapse: collapse; border: 0; margin-bottom: 16px;">
    <tr>
        {{-- KOLOM KIRI: TABEL URAIAN DAN JUMLAH --}}
        <td class="summary-left-col" style="width: 54%; padding-right: 12px; vertical-align: top; border: 0;">
            <table class="formal-table" style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 9.5pt; color: #000000;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #000000; padding: 6px 8px; text-align: left; width: 62%; font-weight: bold;">Uraian</th>
                        <th style="border: 1px solid #000000; padding: 6px 8px; text-align: right; width: 38%; font-weight: bold;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #000000; padding: 6px 8px;">Total Berkas Pelayanan</td>
                        <td style="border: 1px solid #000000; padding: 6px 8px; text-align: right; font-weight: bold;">{{ number_format($total, 0, ',', '.') }} Berkas</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; padding: 6px 8px;">Berkas Selesai</td>
                        <td style="border: 1px solid #000000; padding: 6px 8px; text-align: right; font-weight: bold;">{{ number_format($totalSelesai, 0, ',', '.') }} Berkas</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; padding: 6px 8px;">Berkas Dalam Proses</td>
                        <td style="border: 1px solid #000000; padding: 6px 8px; text-align: right; font-weight: bold;">{{ number_format($totalProses, 0, ',', '.') }} Berkas</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="border: 1px solid #000000; padding: 6px 8px; font-weight: bold;">Persentase Penyelesaian</td>
                        <td style="border: 1px solid #000000; padding: 6px 8px; text-align: right; font-weight: bold;">{{ $persenTuntas }}%</td>
                    </tr>
                </tbody>
            </table>
        </td>

        {{-- KOLOM KANAN: KETERANGAN RINGKAS SEJAJAR HORIZONTAL --}}
        <td class="summary-right-col" style="width: 46%; padding-left: 12px; vertical-align: top; border: 0;">
            <div class="summary-brief-box" style="border: 1px solid #000000; padding: 10px 14px; background: #ffffff; font-size: 9.5pt; line-height: 1.5; min-height: 120px;">
                <div style="font-weight: bold; margin-bottom: 4px; text-decoration: underline;">Keterangan Ringkasan:</div>
                <ul style="margin: 4px 0 8px; padding-left: 18px;">
                    <li>Total pelayanan: <strong>{{ number_format($total, 0, ',', '.') }}</strong> berkas</li>
                    <li>Berkas selesai: <strong>{{ number_format($totalSelesai, 0, ',', '.') }}</strong> berkas</li>
                    <li>Dalam proses: <strong>{{ number_format($totalProses, 0, ',', '.') }}</strong> berkas</li>
                    <li>Penyelesaian: <strong>{{ $persenTuntas }}%</strong></li>
                </ul>
                <div style="font-size: 8.8pt; color: #333333; line-height: 1.35; margin-top: 4px;">
                    @if ($total === 0)
                        <em>Belum ada pengajuan berkas pelayanan yang terdaftar untuk filter yang dipilih.</em>
                    @elseif ($totalSelesai === $total)
                        <em>Seluruh permohonan pelayanan administrasi telah diselesaikan 100% oleh petugas.</em>
                    @elseif ($totalProses > 0)
                        <em>Sebagian permohonan saat ini masih dalam proses penanganan oleh petugas operator.</em>
                    @endif
                </div>
            </div>
        </td>
    </tr>
</table>

{{-- 4. BAGIAN B — RINCIAN REKAPITULASI PELAYANAN (TABEL 7 JENIS PELAYANAN) --}}
<div class="section-heading" style="font-size: 11pt; font-weight: bold; color: #000000; margin-top: 14px; margin-bottom: 8px; text-transform: uppercase;">
    B. RINCIAN REKAPITULASI PELAYANAN
</div>

<table class="formal-table" style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 9.5pt; color: #000000; margin-bottom: 20px;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="border: 1px solid #000000; padding: 6px 6px; width: 36px; text-align: center; font-weight: bold;">No.</th>
            <th style="border: 1px solid #000000; padding: 6px 8px; text-align: left; font-weight: bold;">Jenis Pelayanan</th>
            <th style="border: 1px solid #000000; padding: 6px 8px; width: 90px; text-align: center; font-weight: bold;">Total Berkas</th>
            <th style="border: 1px solid #000000; padding: 6px 8px; width: 75px; text-align: center; font-weight: bold;">Selesai</th>
            <th style="border: 1px solid #000000; padding: 6px 8px; width: 95px; text-align: center; font-weight: bold;">Dalam Proses</th>
            <th style="border: 1px solid #000000; padding: 6px 8px; width: 85px; text-align: center; font-weight: bold;">Persentase</th>
            <th style="border: 1px solid #000000; padding: 6px 8px; width: 145px; text-align: left; font-weight: bold;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($services as $index => $item)
            @php
                $pct = $item['count'] > 0 ? round(($item['selesai'] / $item['count']) * 100, 1) : 0;
                if ($item['count'] === 0) {
                    $keterangan = 'Belum Ada Data';
                } elseif ($item['proses'] === 0 && $item['selesai'] > 0) {
                    $keterangan = 'Pelayanan Selesai 100%';
                } elseif ($item['selesai'] === 0 && $item['proses'] > 0) {
                    $keterangan = 'Sedang Diproses Operator';
                } else {
                    $keterangan = 'Pelayanan Berjalan Optimal';
                }
            @endphp
            <tr>
                <td style="border: 1px solid #000000; padding: 5px 6px; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000000; padding: 5px 8px; text-align: left; font-weight: 500;">{{ $item['label'] }}</td>
                <td style="border: 1px solid #000000; padding: 5px 8px; text-align: center;">{{ number_format($item['count'], 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; padding: 5px 8px; text-align: center;">{{ number_format($item['selesai'], 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; padding: 5px 8px; text-align: center;">{{ number_format($item['proses'], 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000; padding: 5px 8px; text-align: center; font-weight: bold;">{{ $pct }}%</td>
                <td style="border: 1px solid #000000; padding: 5px 8px; text-align: left;">{{ $keterangan }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="border: 1px solid #000000; text-align: center; padding: 16px;">Belum Ada Data</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <td colspan="2" style="border: 1px solid #000000; padding: 6px 8px; text-align: right; font-weight: bold;">TOTAL:</td>
            <td style="border: 1px solid #000000; padding: 6px 8px; text-align: center;">{{ number_format($total, 0, ',', '.') }}</td>
            <td style="border: 1px solid #000000; padding: 6px 8px; text-align: center;">{{ number_format($totalSelesai, 0, ',', '.') }}</td>
            <td style="border: 1px solid #000000; padding: 6px 8px; text-align: center;">{{ number_format($totalProses, 0, ',', '.') }}</td>
            <td style="border: 1px solid #000000; padding: 6px 8px; text-align: center;">{{ $persenTuntas }}%</td>
            <td style="border: 1px solid #000000; padding: 6px 8px; text-align: left;">Terverifikasi</td>
        </tr>
    </tfoot>
</table>

{{-- 5. BAGIAN TANDA TANGAN CAMAT (DI KANAN BAWAH) --}}
<div class="signature-block" style="display: flex; justify-content: flex-end; margin-top: 24px; page-break-inside: avoid;">
    <div class="signature-box" style="text-align: center; width: 270px; font-size: 10pt; line-height: 1.4; color: #000000;">
        <div style="font-weight: bold; margin-bottom: 65px; letter-spacing: 0.5px;">CAMAT JATISARI</div>
        <div class="signature-name" style="font-weight: bold; text-decoration: underline;">H. SURISNO.SKM.MAP</div>
        <div style="font-size: 9.5pt; margin-top: 2px;">NIP. 19690516 1989903 1 003</div>
    </div>
</div>
