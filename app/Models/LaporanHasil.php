<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanHasil extends Model
{
    protected $table = 'laporan_hasil';

    protected $fillable = ['pengajuan_id', 'file_path', 'file_nama_asli', 'file_size', 'status', 'catatan_validator'];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
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
