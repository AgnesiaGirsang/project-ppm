<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuaranRealisasi extends Model
{
    protected $table = 'luaran_realisasi';

    protected $fillable = [
        'pengajuan_luaran_id', 'keterangan', 'link_bukti', 'file_path',
        'file_nama_asli', 'file_size', 'tanggal_realisasi', 'status', 'catatan_validator',
    ];

    protected function casts(): array
    {
        return ['tanggal_realisasi' => 'date'];
    }

    public function pengajuanLuaran()
    {
        return $this->belongsTo(PengajuanLuaran::class);
    }

    public function statusLabel(): array
    {
        return match ($this->status) {
            'proses' => ['Dalam Proses', 'b-menunggu'],
            'disetujui' => ['Disetujui', 'b-disetujui'],
            'revisi' => ['Direvisi', 'b-revisi'],
            default => [$this->status, 'b-menunggu'],
        };
    }
}
