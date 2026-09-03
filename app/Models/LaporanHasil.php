<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanHasil extends Model
{
    protected $table = 'laporan_hasil';

    protected $fillable = [
        'pengajuan_id', 'ringkasan_hasil', 'file_path', 'file_nama_asli', 'file_size',
        'kwitansi_path', 'kwitansi_nama_asli', 'kwitansi_size',
        'bukti_pajak_path', 'bukti_pajak_nama_asli', 'bukti_pajak_size',
        'berita_acara_path', 'berita_acara_nama_asli', 'berita_acara_size',
        'link_inovasi_produk', 'no_sk', 'luaran_tercapai', 'luaran_tambahan_lain', 'dokumentasi',
        'persentase', 'status', 'catatan_validator', 'divalidasi_oleh', 'divalidasi_pada',
    ];

    protected $casts = [
        'luaran_tercapai' => 'array',
        'luaran_tambahan_lain' => 'array',
        'dokumentasi' => 'array',
        'divalidasi_pada' => 'datetime',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function validator()
    {
        return $this->belongsTo(Pegawai::class, 'divalidasi_oleh');
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