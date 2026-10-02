<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'name' => 'Camat Jatisari',
                'email' => 'camat@patenspace.test',
                'role' => 'camat',
                'nik' => '3200000000000001',
                'phone' => '081200000001',
            ],
            [
                'name' => 'Operator PATEN',
                'email' => 'operator@patenspace.test',
                'role' => 'operator',
                'nik' => '3200000000000002',
                'phone' => '081200000002',
            ],
            [
                'name' => 'Admin PATEN',
                'email' => 'admin@patenspace.test',
                'role' => 'admin',
                'nik' => '3200000000000003',
                'phone' => '081200000003',
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                array_merge($account, [
                    'password' => Hash::make('Paten@12345'),
                    'terms_accepted' => true,
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
