<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\LegalDocument;
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
        // --- User test default ---
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'              => 'Test User',
                'password'          => Hash::make('password'),
                'role'              => 'marketing',
                'email_verified_at' => now(),
            ]
        );

        // --- Akun admin default MABIPRO ---
        $this->call(AdminSeeder::class);

        // --- Blocks ---
        $blokA = Block::firstOrCreate(['nama_blok' => 'A - Riverside']);
        $blokB = Block::firstOrCreate(['nama_blok' => 'B - Mountain View']);

        // --- Unit A-01 (Terjual, legalitas lengkap) ---
        $unit1 = Unit::firstOrCreate(
            ['block_id' => $blokA->id, 'unit_number' => 'A-01'],
            [
                'status_penjualan'    => 'Terjual',
                'progres_pembangunan' => 100,
                'status_legalitas'    => 'Lengkap',
            ]
        );

        // --- Unit A-02 (Sudah DP, legalitas belum lengkap) ---
        $unit2 = Unit::firstOrCreate(
            ['block_id' => $blokA->id, 'unit_number' => 'A-02'],
            [
                'status_penjualan'    => 'Sudah DP',
                'progres_pembangunan' => 45,
                'status_legalitas'    => 'Belum Lengkap',
            ]
        );

        // --- Unit B-01 (Belum Terjual) ---
        Unit::firstOrCreate(
            ['block_id' => $blokB->id, 'unit_number' => 'B-01'],
            [
                'status_penjualan'    => 'Belum Terjual',
                'progres_pembangunan' => 0,
                'status_legalitas'    => 'Belum Lengkap',
            ]
        );

        // --- Dokumen Legalitas untuk A-01 (lengkap) ---
        LegalDocument::firstOrCreate(
            ['unit_id' => $unit1->id, 'document_name' => 'Sertifikat Tanah (SHM)'],
            [
                'document_number' => 'SHM.12345.01',
                'file_path'       => 'dummy/path/shm.pdf',
                'uploaded_by'     => $user->id,
            ]
        );

        LegalDocument::firstOrCreate(
            ['unit_id' => $unit1->id, 'document_name' => 'Izin Bangunan (IMB)'],
            [
                'document_number' => 'IMB.12345.01',
                'file_path'       => 'dummy/path/imb.pdf',
                'uploaded_by'     => $user->id,
            ]
        );

        // --- Dokumen Legalitas untuk A-02 (belum lengkap, hanya satu dokumen) ---
        LegalDocument::firstOrCreate(
            ['unit_id' => $unit2->id, 'document_name' => 'Sertifikat Tanah (SHM)'],
            [
                'document_number' => 'SHM.12345.02',
                'file_path'       => 'dummy/path/shm2.pdf',
                'uploaded_by'     => $user->id,
            ]
        );
    }
}
