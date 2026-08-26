<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pegawai extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nip', 'nama', 'password', 'role', 'jabatan', 'pangkat',
        'jurusan', 'prodi', 'email', 'hp', 'nidn', 'foto', 'must_change_password',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'must_change_password' => 'boolean',
        ];
    }

    /**
     * Inisial nama buat avatar bulat di topbar/sidebar.
     * Contoh: "Dr. Budi Santoso" -> "BS"
     */
    public function initials(): string
    {
        $clean = str_replace(['Dr.', 'Ns.', 'M.Kep'], '', $this->nama);
        $parts = array_values(array_filter(explode(' ', trim($clean))));
        $parts = array_slice($parts, 0, 2);

        if (empty($parts)) {
            return $this->role === 'admin' ? 'A' : 'D';
        }

        return strtoupper(implode('', array_map(fn ($p) => $p[0], $parts)));
    }
}
