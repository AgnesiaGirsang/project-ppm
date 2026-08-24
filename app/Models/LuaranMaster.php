<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuaranMaster extends Model
{
    protected $table = 'luaran_master';

    protected $fillable = ['jenis', 'nama', 'wajib', 'opsi'];

    protected function casts(): array
    {
        return [
            'wajib' => 'boolean',
            'opsi' => 'string', // Ubah jika opsi disimpan sebagai string/teks biasa
        ];
    }
}