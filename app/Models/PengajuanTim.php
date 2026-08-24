<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanTim extends Model
{
    protected $table = 'pengajuan_tim';

    protected $fillable = ['pengajuan_id', 'pegawai_id', 'nama_luar', 'instansi_luar', 'peran'];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function isDariSistem(): bool
    {
        return !is_null($this->pegawai_id);
    }

    // Nama yang ditampilkan, entah dari akun sistem atau input manual
    public function namaTampil(): string
    {
        return $this->pegawai ? $this->pegawai->nama : ($this->nama_luar ?? '-');
    }

    public function nipTampil(): ?string
    {
        return $this->pegawai ? $this->pegawai->nip : $this->instansi_luar;
    }
}
