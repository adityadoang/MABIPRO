<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Users ---
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'              => 'Test User',
                'password'          => Hash::make('password'),
                'role'              => 'marketing',
                'email_verified_at' => now(),
            ]
        );

        // Seed akun admin default MABIPRO
        $this->call(AdminSeeder::class);

        // --- Blocks ---
        $blokA = Block::firstOrCreate(['nama_blok' => 'Blok A']);
        $blokB = Block::firstOrCreate(['nama_blok' => 'Blok B']);

        // --- Units Blok A ---
        $unitsBlokA = [
            ['unit_number' => 'A-01', 'status_penjualan' => 'Terjual',       'payment_method' => 'KPR', 'kpr_duration_months' => 149, 'amount_paid' => 10000000.00, 'payment_proof_path' => 'payment_proofs/8JOHSPzTm1XhQs5jJPERb39BhVdRlSJD5Bf2LV4K.png'],
            ['unit_number' => 'A-02', 'status_penjualan' => 'Sudah DP',      'amount_paid' => 500000000.00],
            ['unit_number' => 'A-03', 'status_penjualan' => 'Terjual'],
            ['unit_number' => 'A-04', 'status_penjualan' => 'Belum Terjual'],
            ['unit_number' => 'A-05', 'status_penjualan' => 'Belum Terjual'],
        ];

        foreach ($unitsBlokA as $data) {
            Unit::firstOrCreate(
                ['block_id' => $blokA->id, 'unit_number' => $data['unit_number']],
                array_merge([
                    'progres_pembangunan' => 0,
                    'status_legalitas'    => 'Belum Lengkap',
                ], $data)
            );
        }

        // --- Units Blok B ---
        $unitsBlokB = [
            ['unit_number' => 'B-01', 'status_penjualan' => 'Sudah DP'],
            ['unit_number' => 'B-02', 'status_penjualan' => 'Sudah DP'],
            ['unit_number' => 'B-03', 'status_penjualan' => 'Sudah DP'],
        ];

        foreach ($unitsBlokB as $data) {
            Unit::firstOrCreate(
                ['block_id' => $blokB->id, 'unit_number' => $data['unit_number']],
                array_merge([
                    'progres_pembangunan' => 0,
                    'status_legalitas'    => 'Belum Lengkap',
                ], $data)
            );
        }
    }
}
