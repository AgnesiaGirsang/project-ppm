<?php

namespace App\Imports;

use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PegawaiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Pegawai::updateOrCreate(
                ['nip' => $row['nip']], 
                [
                    'nama'                 => $row['nama'],
                    'password'             => isset($row['password']) ? Hash::make($row['password']) : Hash::make('password123'),
                    'role'                 => $row['role'] ?? 'dosen',
                    'jabatan'              => $row['jabatan'] ?? null,
                    'pangkat'              => $row['pangkat'] ?? null,
                    'jurusan'              => $row['jurusan'] ?? null,
                    'prodi'                => $row['prodi'] ?? null,
                    'email'                => $row['email'] ?? null,
                    'hp'                   => $row['hp'] ?? null,
                    'nidn'                 => $row['nidn'] ?? null,
                    'must_change_password' => 1,
                ]
            );
        }
    }
}