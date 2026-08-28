<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    protected $fillable = [
        'kode', 'pegawai_id', 'jenis', 'jalur', 'skema_id', 'rumpun_ilmu_id',
        'judul', 'tahun_anggaran', 'tahun_pengajuan', 'tahun_pelaksanaan', 'tahun_capaian',
        'proposal_path', 'proposal_nama_asli', 'proposal_size',
        'total_biaya', 'inovasi_produk', 'tahap', 'status', 'catatan_validator',
    ];

    protected function casts(): array
    {
        return [
            'total_biaya' => 'decimal:2',
        ];
    }

    // Ketua pengaju
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function rumpunIlmu()
    {
        return $this->belongsTo(RumpunIlmu::class);
    }

    // Semua anggota tim (termasuk ketua)
    public function tim()
    {
        return $this->hasMany(PengajuanTim::class);
    }

    // Alias 'anggotas' agar cocok dengan pemanggilan with(['anggotas']) di controller
    public function anggotas()
    {
        return $this->hasMany(PengajuanTim::class, 'pengajuan_id');
    }

    public function anggotaTim()
    {
        return $this->tim()->where('peran', 'anggota');
    }

    public function luaran()
    {
        return $this->hasMany(PengajuanLuaran::class);
    }

    public function laporanKemajuan()
    {
        return $this->hasOne(LaporanKemajuan::class);
    }

    public function laporanHasil()
    {
        return $this->hasOne(LaporanHasil::class);
    }

    // Label status buat ditampilin di badge (samain sama prototype)
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
