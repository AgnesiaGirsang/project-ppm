<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanHasil extends Model
{
    protected $table = 'laporan_hasil';

    protected $fillable = [
        'pengajuan_id', 'ringkasan_hasil', 'file_path', 'file_nama_asli', 'file_size',
        'link_inovasi_produk', 'no_sk', 'luaran_tercapai', 'dokumentasi',
        'persentase', 'status', 'catatan_validator',
    ];

    protected $casts = [
        'luaran_tercapai' => 'array',
        'dokumentasi' => 'array',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function statusLabel(): array
    {
        return match ($this->status) {
            'draft' => ['Draft', 'b-menunggu'],
            'proses' => ['Dalam Proses', 'b-menunggu'],
            'disetujui' => ['Disetujui', 'b-disetujui'],
            'revisi' => ['Direvisi', 'b-revisi'],
            default => [$this->status, 'b-menunggu'],
        };
    }
}
