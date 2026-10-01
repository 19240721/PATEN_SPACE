<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DemoPendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $people = [
            ['nik' => '3327082809900102', 'name' => 'ANDRI WIBOWO', 'gender' => 'L', 'jalan' => 'KP. KARYAWAN', 'rt' => '2', 'rw' => '1', 'desa' => 'JATIRAGAS', 'kk_reason' => 'PENAMBAHAN ANGGOTA KELUARGA'],
            ['nik' => '3215242204710002', 'name' => 'MAMAN SETIAWAN', 'gender' => 'L', 'jalan' => 'KARAJAN', 'rt' => '1', 'rw' => '2', 'desa' => 'JATIBARU', 'kk_reason' => 'BUAT KK BARU (PINDAH)'],
            ['nik' => '3215144708660004', 'name' => 'NAAH', 'gender' => 'P', 'jalan' => 'SAWAH BARU', 'rt' => '1', 'rw' => '7', 'desa' => 'JATIBARU', 'kk_reason' => 'PENAMBAHAN ANGGOTA KELUARGA'],
            ['nik' => '3215144205850003', 'name' => 'ROHATI', 'gender' => 'P', 'jalan' => 'DUSUN 2 BARUGBUG', 'rt' => '2', 'rw' => '5', 'desa' => 'BARUGBUG', 'kk_reason' => 'PERUBAHAN DATA'],
            ['nik' => '3215145502750001', 'name' => 'NINING YUNINGSIH', 'gender' => 'P', 'jalan' => 'KRAJAN I', 'rt' => '2', 'rw' => '1', 'desa' => 'JATIWANGI', 'kk_reason' => 'PENGHAPUSAN ANGGOTA KELUARGA'],
            ['nik' => '3215142608870001', 'name' => 'UJANG ANWAR', 'gender' => 'L', 'jalan' => 'DUSUN KERTASARI', 'rt' => '1', 'rw' => '5', 'desa' => 'BALONGGANDU', 'kk_reason' => 'BUAT KK BARU'],
            ['nik' => '3215146708880003', 'name' => 'RESAH', 'gender' => 'P', 'jalan' => 'JEBUG II', 'rt' => '1', 'rw' => '5', 'desa' => 'SUKAMEKAR', 'kk_reason' => 'PERUBAHAN DATA'],
        ];

        $columns = array_flip(Schema::getColumnListing('pendaftarans'));
        $records = [];
        $reasons = ['Hilang', 'Rusak', 'Update Data'];
        $arrivalAddresses = [
            [
                'origin' => 'KP. KADU TALEOY, 02/02, CIGANDENG, MENES, PANDEGLANG, BANTEN',
                'destination' => 'CIKALONG BUNDER, 02/03, JATIRAGAS, JATISARI',
            ],
            [
                'origin' => 'KP SUKASARI, 08/03, KARYAMEKAR, CIBATU',
                'destination' => 'BABAKAN KAREO, 02/08, PACING, JATISARI',
            ],
            [
                'origin' => 'KP. GEMPOL PASAR SELATAN, 02/02, GEMPOL, BANYUSARI',
                'destination' => 'KARAJAN, 01/02, JATIBARU',
            ],
        ];

        foreach ($people as $index => $person) {
            $number = $index + 1;
            $fullAddress = $person['jalan'] . ', RT ' . $person['rt'] . '/RW ' . $person['rw'] . ', ' . $person['desa'];
            $arrivalAddress = $index < count($arrivalAddresses) * 2
                ? $arrivalAddresses[$index % count($arrivalAddresses)]
                : [
                    'origin' => 'KRAJAN, 01/03, JATISARI, KARAWANG',
                    'destination' => 'DUSUN KERTASARI, 02/04, BALONGGANDU, JATISARI',
                ];
            $common = [
                'nama_lengkap' => $person['name'],
                'nik' => $person['nik'],
                'jenis_kelamin' => $person['gender'],
                'tempat_lahir' => 'Karawang',
                'tanggal_lahir' => $this->birthDateFromNik($person['nik'], $person['gender']),
                'alamat' => $fullAddress,
                'jalan' => $person['jalan'],
                'rt' => $person['rt'],
                'rw' => $person['rw'],
                'desa' => $person['desa'],
                'kecamatan' => 'Jatisari',
                'petugas' => 'Petugas Kecamatan',
                'tanggal_daftar' => '2026-09-22',
                'status' => ['Baru', 'Proses', 'Selesai'][$index % 3],
                'bulan' => 'SEP',
                'minggu_ke' => '4',
            ];

            $serviceDetails = [
                'KK' => [
                    'keterangan_kk' => $person['kk_reason'],
                ],
                'PRR' => [
                    'tanggal_kedatangan' => '2026-09-22',
                ],
                'KTP' => [
                    'alasan_pembaruan' => $reasons[$index % count($reasons)],
                ],
                'Akta Kematian' => [
                    'nama_alm' => $person['name'],
                ],
                'Akta Lahir' => [
                    'nama_anak_lahir' => $person['name'],
                ],
                'Kedatangan' => [
                    'nama_kedatangan' => $person['name'],
                    'nik_kedatangan' => $person['nik'],
                    'alamat_asal_kedatangan' => $arrivalAddress['origin'] ?? null,
                    'alamat_tujuan_kedatangan' => $arrivalAddress['destination'] ?? null,
                    'tanggal_kedatangan' => sprintf('2026-09-%02d', 22 + $index),
                ],
                'Pindah' => [
                    'nama_pindah' => $person['name'],
                    'nik_pindah' => $person['nik'],
                    'alamat_asal_pindah' => $fullAddress,
                ],
            ];

            foreach ($serviceDetails as $serviceType => $details) {
                $records[] = array_merge($common, [
                    'jenis_layanan' => $serviceType,
                ], $details);
            }
        }

        $columnsToClear = array_diff(array_keys($columns), ['id', 'created_at', 'updated_at']);
        $emptyAttributes = array_fill_keys($columnsToClear, null);

        foreach ($records as $record) {
            $attributes = array_intersect_key(array_merge($emptyAttributes, $record), $columns);

            Pendaftaran::updateOrCreate(
                ['nik' => $record['nik'], 'jenis_layanan' => $record['jenis_layanan']],
                $attributes,
            );
        }
    }

    private function birthDateFromNik(string $nik, string $gender): ?string
    {
        if (!preg_match('/^\d{16}$/', $nik)) {
            return null;
        }

        $day = (int) substr($nik, 6, 2);
        if ($gender === 'P') {
            $day -= 40;
        }

        $month = (int) substr($nik, 8, 2);
        $shortYear = (int) substr($nik, 10, 2);
        $year = $shortYear <= 26 ? 2000 + $shortYear : 1900 + $shortYear;

        return checkdate($month, $day, $year) ? sprintf('%04d-%02d-%02d', $year, $month, $day) : null;
    }
}
