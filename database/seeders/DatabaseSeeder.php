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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $blokA = \App\Models\Block::create(['name' => 'Blok A']);
        $blokB = \App\Models\Block::create(['name' => 'Blok B']);

        foreach (range(1, 5) as $i) {
            \App\Models\Unit::create([
                'block_id' => $blokA->id,
                'unit_number' => 'A-0' . $i,
                'sales_status' => 'Belum Terjual'
            ]);
        }

        foreach (range(1, 3) as $i) {
            \App\Models\Unit::create([
                'block_id' => $blokB->id,
                'unit_number' => 'B-0' . $i,
                'sales_status' => 'Sudah DP'
            ]);
        }
    }
}
