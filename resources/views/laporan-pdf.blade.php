<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Laporan Pelayanan PATEN</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 15px;
        }

        .header p {
            margin: 5px 0;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul h2 {
            margin: 0;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background: #d9eaf7;
            text-align: center;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 7px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .total-box {
            text-align: center;
            border: 1px solid #999;
            padding: 12px;
            margin-bottom: 20px;
        }

        .total-number {
            font-size: 24px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- KOP --}}
    <div class="header">

        <h2>PEMERINTAH KABUPATEN KARAWANG</h2>

        <h3>KECAMATAN JATISARI</h3>

        <p>
            Pelayanan Administrasi Terpadu Kecamatan (PATEN)
        </p>

    </div>


    {{-- JUDUL --}}
    <div class="judul">

        <h2>LAPORAN DATA PELAYANAN PATEN</h2>

        <p>Kecamatan Jatisari</p>

    </div>


    {{-- TOTAL --}}
    <div class="total-box">

        <div class="total-number">
            {{ number_format($totalPelayanan, 0, ',', '.') }}
        </div>

        <div>
            Total Pelayanan
        </div>

    </div>


    {{-- REKAP --}}
    <h4>Rekap Berdasarkan Jenis Pelayanan</h4>

    <table>

        <thead>
            <tr>
                <th width="10%">No</th>
                <th>Jenis Pelayanan</th>
                <th width="20%">Jumlah</th>
                <th width="20%">Persentase</th>
            </tr>
        </thead>

        <tbody>

            @forelse($rekapLayanan as $jenis => $jumlah)

                @php
                    $persentase = $totalPelayanan > 0
                        ? round(($jumlah / $totalPelayanan) * 100)
                        : 0;
                @endphp

                <tr>

                    <td class="center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $jenis }}
                    </td>

                    <td class="right">
                        {{ number_format($jumlah, 0, ',', '.') }}
                    </td>

                    <td class="right">
                        {{ $persentase }}%
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4" class="center">
                        Belum ada data pelayanan.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- DAFTAR PENDAFTARAN --}}
    <h4>Daftar Data Pendaftaran</h4>

    <table>

        <thead>

            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NIK</th>
                <th>Jenis Pelayanan</th>
                <th>Petugas</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>

        </thead>

        <tbody>

            @forelse($pendaftarans as $data)

                <tr>

                    <td class="center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $data->nama_lengkap ?? '-' }}
                    </td>

                    <td>
                        {{ $data->nik ?? '-' }}
                    </td>

                    <td>
                        {{ $data->jenis_layanan ?? '-' }}
                    </td>

                    <td>
                        {{ $data->petugas ?? '-' }}
                    </td>

                    <td>
                        {{ $data->tanggal_daftar ?? '-' }}
                    </td>

                    <td>
                        {{ $data->status ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="center">
                        Belum ada data pendaftaran.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="footer">

        Jatisari, {{ date('d-m-Y') }}

        <br>
        <br>
        <br>

        <strong>Petugas Pelayanan</strong>

    </div>

</body>
</html>