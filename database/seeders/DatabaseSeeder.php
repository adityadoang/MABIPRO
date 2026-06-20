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
        // 1. Create User
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'marketing',
        ]);

        // 2. Create Blocks
        $blockA = \App\Models\Block::create(['nama_blok' => 'A - Riverside']);
        $blockB = \App\Models\Block::create(['nama_blok' => 'B - Mountain View']);

        // 3. Create Units
        // Unit A-01 (Complete)
        $unit1 = \App\Models\Unit::create([
            'block_id' => $blockA->id,
            'unit_number' => 'A-01',
            'status_penjualan' => 'Terjual',
            'progres_pembangunan' => 100,
            'status_legalitas' => 'Lengkap',
        ]);

        // Unit A-02 (Missing)
        $unit2 = \App\Models\Unit::create([
            'block_id' => $blockA->id,
            'unit_number' => 'A-02',
            'status_penjualan' => 'Sudah DP',
            'progres_pembangunan' => 45,
            'status_legalitas' => 'Belum Lengkap',
        ]);

        // Unit B-01 (Missing)
        $unit3 = \App\Models\Unit::create([
            'block_id' => $blockB->id,
            'unit_number' => 'B-01',
            'status_penjualan' => 'Belum Terjual',
            'progres_pembangunan' => 0,
            'status_legalitas' => 'Belum Lengkap',
        ]);

        // 4. Create Documents for A-01 to make it Complete
        \App\Models\LegalDocument::create([
            'unit_id' => $unit1->id,
            'document_name' => 'Sertifikat Tanah (SHM)',
            'document_number' => 'SHM.12345.01',
            'file_path' => 'dummy/path/shm.pdf',
            'uploaded_by' => $user->id,
        ]);

        \App\Models\LegalDocument::create([
            'unit_id' => $unit1->id,
            'document_name' => 'Izin Bangunan (IMB)',
            'document_number' => 'IMB.12345.01',
            'file_path' => 'dummy/path/imb.pdf',
            'uploaded_by' => $user->id,
        ]);

        // 5. Create Documents for A-02 (Missing one)
        \App\Models\LegalDocument::create([
            'unit_id' => $unit2->id,
            'document_name' => 'Sertifikat Tanah (SHM)',
            'document_number' => 'SHM.12345.02',
            'file_path' => 'dummy/path/shm2.pdf',
            'uploaded_by' => $user->id,
        ]);
    }
}
