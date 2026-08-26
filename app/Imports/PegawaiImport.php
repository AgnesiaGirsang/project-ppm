<?php

namespace App\Imports;

use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PegawaiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            // Lewati baris kosong (misal baris kosong di tengah/akhir file Excel)
            if (empty($row['nip'])) {
                continue;
            }

            Pegawai::updateOrCreate(
                ['nip' => $row['nip']],
                [
                    'nama'                 => $row['nama'] ?? $row['nama_lengkap'] ?? '-',
                    'password'             => !empty($row['password']) ? Hash::make($row['password']) : Hash::make('password123'),
                    'role'                 => $row['role'] ?? 'dosen',
                    'jabatan'              => $row['jabatan'] ?? null,
                    'pangkat'              => $row['pangkat'] ?? null,
                    'jurusan'              => $row['jurusan'] ?? null,
                    'prodi'                => $row['prodi'] ?? null,
                    'email'                => $row['email'] ?? null,
                    'hp'                   => $row['hp'] ?? $row['no_hp'] ?? null,
                    'nidn'                 => $row['nidn'] ?? null,
                    'must_change_password' => 1,
                ]
            );
        }
    }
}
