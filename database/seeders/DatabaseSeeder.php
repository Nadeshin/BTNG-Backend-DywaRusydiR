<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MobilSeeder::class,
        ]);

        // Akun demo (dipakai kalau nanti menambah otentikasi Sanctum).
        User::firstOrCreate(
            ['email' => 'admin@showroom.test'],
            ['name' => 'Admin Showroom', 'password' => bcrypt('password')]
        );
    }
}
