<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuaranMaster extends Model
{
    protected $table = 'luaran_masters';

    protected $fillable = ['jenis', 'nama', 'opsi_indikator'];

    protected function casts(): array
    {
        return [
            'opsi_indikator' => 'array',
        ];
    }
}
