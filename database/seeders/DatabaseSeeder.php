<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Petugas PATEN',
                'email' => 'petugas@patenspace.test',
                'role' => 'petugas',
                'nik' => '3200000000000004',
                'phone' => '081200000004',
            ],
            [
                'name' => 'Camat Jatisari',
                'email' => 'camat@patenspace.test',
                'role' => 'camat',
                'nik' => '3200000000000001',
                'phone' => '081200000001',
            ],
            [
                'name' => 'Masyarakat PATEN',
                'email' => 'masyarakat@patenspace.test',
                'role' => 'masyarakat',
                'nik' => '3200000000000005',
                'phone' => '081200000005',
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                array_merge($account, [
                    'password' => 'Paten@12345',
                    'terms_accepted' => true,
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
