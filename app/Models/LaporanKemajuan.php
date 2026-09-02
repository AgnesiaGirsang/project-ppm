<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKemajuan extends Model
{
    protected $table = 'laporan_kemajuan';

    protected $fillable = [
        'pengajuan_id', 'persentase', 'file_path', 'file_nama_asli', 'file_size',
        'dokumentasi', 'kegiatan_dilakukan', 'kendala', 'rencana_berikutnya', 'komentar',
        'luaran_tercapai', 'status', 'catatan_validator',
        'divalidasi_oleh', 'divalidasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'dokumentasi' => 'array',
            'luaran_tercapai' => 'array',
            'divalidasi_pada' => 'datetime',
        ];
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    // Admin yang melakukan validasi terakhir (gate dari akun yang login saat Kirim Keputusan)
    public function validator()
    {
        return $this->belongsTo(\App\Models\Pegawai::class, 'divalidasi_oleh');
    }

    public function statusLabel(): array
    {
        return match ($this->status) {
            'draft' => ['Draft', 'b-menunggu'],
            'proses' => ['Menunggu Validasi', 'b-menunggu'],
            'disetujui' => ['Disetujui', 'b-disetujui'],
            'revisi' => ['Perlu Direvisi', 'b-revisi'],
            default => [$this->status, 'b-menunggu'],
        };
    }
}
