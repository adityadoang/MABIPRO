<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed akun admin default MABIPRO.
     * Menggunakan updateOrCreate agar idempotent — aman dijalankan berulang kali.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mabipro.test'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'Admin',
            ]
        );

        $this->command->info('✔ Akun admin default berhasil dibuat: admin@mabipro.test / password');
    }
}
