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
        'kontrak_path', 'kontrak_nama_asli', 'kontrak_size',
        'rab_path', 'rab_nama_asli', 'rab_size',
        'kwitansi_path', 'kwitansi_nama_asli', 'kwitansi_size',
        'bukti_pajak_path', 'bukti_pajak_nama_asli', 'bukti_pajak_size',
        'berita_acara_path', 'berita_acara_nama_asli', 'berita_acara_size',
        'total_biaya', 'inovasi_produk', 'tahap', 'status', 'catatan_validator',
        'divalidasi_oleh', 'divalidasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'total_biaya' => 'decimal:2',
            'divalidasi_pada' => 'datetime',
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

    // Alias 'anggotas' agar cocok dengan pemanggilan with(['anggotas']) di controller.
    // Difilter khusus peran 'anggota' supaya ketua tidak ikut ter-loop sebagai
    // anggota di halaman-halaman yang memakai relasi ini (mis. Validasi Proposal admin).
    public function anggotas()
    {
        return $this->hasMany(PengajuanTim::class, 'pengajuan_id')->where('peran', 'anggota');
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

    // Admin yang melakukan validasi terakhir (gate dari akun yang login saat Kirim Keputusan)
    public function validator()
    {
        return $this->belongsTo(\App\Models\Pegawai::class, 'divalidasi_oleh');
    }

    // Label status buat ditampilin di badge (samain sama prototype)
    public function statusLabel(): array
    {
        // Kalau pengajuan sudah masuk tahap Laporan Kemajuan/Hasil, status yang
        // relevan adalah status laporan di tahap itu — bukan status validasi
        // proposal lama, yang tetap 'disetujui' selamanya sejak proposal lolos.
        if ($this->tahap === 'laporan_kemajuan') {
            return $this->laporanStatusLabel($this->laporanKemajuan?->status);
        }

        if ($this->tahap === 'laporan_hasil') {
            return $this->laporanStatusLabel($this->laporanHasil?->status);
        }

        return match ($this->status) {
            'proses' => ['Dalam Proses', 'b-menunggu'],
            'disetujui' => ['Disetujui', 'b-disetujui'],
            'revisi' => ['Direvisi', 'b-revisi'],
            default => [$this->status, 'b-menunggu'],
        };
    }

    private function laporanStatusLabel(?string $status): array
    {
        return match ($status) {
            'draft' => ['Draft', 'b-menunggu'],
            'proses' => ['Sedang Diproses', 'b-menunggu'],
            'disetujui' => ['Disetujui', 'b-disetujui'],
            'revisi' => ['Direvisi', 'b-revisi'],
            default => ['Dalam Proses', 'b-menunggu'],
        };
    }
}
