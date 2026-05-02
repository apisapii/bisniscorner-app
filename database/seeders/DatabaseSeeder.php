<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Umkm;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Super Admin (Bisa kamu pakai nanti)
        User::create([
            'name' => 'Super Admin Hafiz',
            'email' => 'admin@bazaar.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 2. Buat 1 Toko UMKM Dummy
        $toko = Umkm::create([
            'name' => 'BoMaK! Snack',
            'description' => 'Camilan enak khas bazar kampus',
        ]);

        // 3. Buat Akun Admin khusus untuk Toko BoMaK!
        User::create([
            'name' => 'Admin BoMaK',
            'email' => 'bomak@bazaar.com',
            'password' => Hash::make('password'),
            'role' => 'admin_umkm',
            'umkm_id' => $toko->id,
        ]);
    }
}