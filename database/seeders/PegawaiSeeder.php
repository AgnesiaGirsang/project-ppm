<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        Pegawai::create([
            'nip' => '000000000000000000',
            'nama' => 'Administrator SIPPM',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'jabatan' => 'Admin Sistem',
            'email' => 'admin@poltekkesmedan.ac.id',
            'hp' => '061-8888999',
        ]);

        Pegawai::create([
            'nip' => '198001012008011001',
            'nama' => 'Dr. Budi Santoso',
            'password' => Hash::make('08011001'),
            'role' => 'dosen',
            'jabatan' => 'Lektor',
            'pangkat' => 'III/c · Penata',
            'jurusan' => 'Teknologi Informasi',
            'prodi' => 'D-III Teknik Komputer',
            'email' => 'budi.santoso@poltekkesmedan.ac.id',
            'hp' => '0812-3456-7890',
            'nidn' => '0123456789',
            'must_change_password' => true,
        ]);

        Pegawai::create([
            'nip' => '198203152010012002',
            'nama' => 'Dr. Ani Lestari',
            'password' => Hash::make('15012002'),
            'role' => 'dosen',
            'jabatan' => 'Lektor Kepala',
            'pangkat' => 'IV/a · Pembina',
            'jurusan' => 'Kebidanan',
            'prodi' => 'D-III Kebidanan',
            'email' => 'ani.lestari@poltekkesmedan.ac.id',
            'hp' => '0813-1111-2222',
            'nidn' => '9876543210',
            'must_change_password' => false,
        ]);
    }
}
