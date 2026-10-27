<?php

namespace Database\Seeders;

use App\Models\Mobil;
use Illuminate\Database\Seeder;

class MobilSeeder extends Seeder
{
    /**
     * Mengisi 10 data dummy showroom mobil.
     */
    public function run(): void
    {
        $mobils = [
            [
                'nama' => 'Avanza 1.5 G CVT',
                'brand' => 'Toyota',
                'harga' => 275_000_000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341',
            ],
            [
                'nama' => 'Civic RS Turbo',
                'brand' => 'Honda',
                'harga' => 615_000_000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d',
            ],
            [
                'nama' => 'Xpander Ultimate',
                'brand' => 'Mitsubishi',
                'harga' => 320_000_000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70',
            ],
            [
                'nama' => 'Fortuner 2.4 VRZ',
                'brand' => 'Toyota',
                'harga' => 610_000_000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Diesel',
                'status' => 'sold',
                'foto' => 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8',
            ],
            [
                'nama' => 'Ertiga GX',
                'brand' => 'Suzuki',
                'harga' => 265_000_000,
                'tahun' => 2024,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888',
            ],
            [
                'nama' => 'Ioniq 5 Signature',
                'brand' => 'Hyundai',
                'harga' => 780_000_000,
                'tahun' => 2024,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Listrik',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1550355291-bbee04a92027',
            ],
            [
                'nama' => 'Brio Satya E',
                'brand' => 'Honda',
                'harga' => 175_000_000,
                'tahun' => 2023,
                'transmisi' => 'Manual',
                'bahan_bakar' => 'Bensin',
                'status' => 'sold',
                'foto' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf',
            ],
            [
                'nama' => 'Air EV Long Range',
                'brand' => 'Wuling',
                'harga' => 295_000_000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Listrik',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b',
            ],
            [
                'nama' => 'Terios R',
                'brand' => 'Daihatsu',
                'harga' => 245_000_000,
                'tahun' => 2022,
                'transmisi' => 'Manual',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1502877338535-766e1452684a',
            ],
            [
                'nama' => 'Alphard 2.5 HEV',
                'brand' => 'Toyota',
                'harga' => 1_350_000_000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Hybrid',
                'status' => 'sold',
                'foto' => 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c',
            ],
        ];

        foreach ($mobils as $mobil) {
            Mobil::updateOrCreate(
                ['nama' => $mobil['nama'], 'tahun' => $mobil['tahun']],
                $mobil
            );
        }
    }
}
