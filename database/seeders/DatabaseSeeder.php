<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\HargaCetak;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $Admin = User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'RFID' => '08994805926',
            'status' => 'aktif',
        ]);
    
        $Karyawan = User::create([
            'email' => 'karyawan@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'karyawan',
            'RFID' => '08994805925',
        ]);

        $Pelanggan = User::create([
            'email' => 'pengguna1@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'pelanggan',
            'RFID' => '08994805924',
        ]);
    
        $profileAdmin = admin::create([
            'id_user' => $Admin->id,
            'nama' => 'admin',
        ]);

        $profileKaryawan = karyawan::create([
            'id_user' => $Karyawan->id,
            'nama' => 'karyawan',
        ]);

        $profilePelanggan = pelanggan::create([
            'id_user' => $Pelanggan->id,
            'nama' => 'pelanggan',
        ]);

        HargaCetak::create(['tipe_dokumen' => 'hitam_putih', 'harga' => 1000]);
        HargaCetak::create(['tipe_dokumen' => 'berwarna', 'harga' => 2000]);
    }
}
