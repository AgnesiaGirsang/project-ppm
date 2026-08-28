<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanLuaran extends Model
{
    protected $table = 'pengajuan_luaran';

    protected $fillable = ['pengajuan_id', 'luaran_master_id', 'opsi_dipilih', 'is_wajib'];

    protected function casts(): array
    {
        return [
            'is_wajib' => 'boolean',
        ];
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function luaranMaster()
    {
        return $this->belongsTo(LuaranMaster::class);
    }

    public function realisasi()
    {
        return $this->hasOne(LuaranRealisasi::class);
    }
}