<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    protected $fillable = [
        'kode', 'pegawai_id', 'jenis', 'jalur', 'skema_id', 'rumpun_ilmu_id',
        'judul', 'tahun', 'proposal_path', 'proposal_nama_asli', 'proposal_size',
        'total_biaya', 'inovasi_produk', 'tahap', 'status', 'catatan_validator',
    ];

    protected function casts(): array
    {
        return [
            'total_biaya' => 'decimal:2',
        ];
    }

    // Ketua pengaju (Eksplisit menyertakan foreign key 'pegawai_id' dan local key 'id')
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'skema_id', 'id');
    }

    public function rumpunIlmu()
    {
        return $this->belongsTo(RumpunIlmu::class, 'rumpun_ilmu_id', 'id');
    }

    // Semua anggota tim (termasuk ketua)
    public function tim()
    {
        return $this->hasMany(PengajuanTim::class, 'pengajuan_id', 'id');
    }

    public function anggotaTim()
    {
        return $this->tim()->where('peran', 'anggota');
    }

    public function luaran()
    {
        return $this->hasMany(PengajuanLuaran::class, 'pengajuan_id', 'id');
    }

    public function laporanKemajuan()
    {
        return $this->hasOne(LaporanKemajuan::class, 'pengajuan_id', 'id');
    }

    public function laporanHasil()
    {
        return $this->hasOne(LaporanHasil::class, 'pengajuan_id', 'id');
    }

    // Label status buat ditampilin di badge
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