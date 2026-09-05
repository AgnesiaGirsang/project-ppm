<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatValidasi extends Model
{
    protected $table = 'riwayat_validasi';

    protected $fillable = [
        'validatable_type',
        'validatable_id',
        'admin_id',
        'tahap',
        'status',
        'catatan',
        'dilakukan_pada',
    ];

    protected $casts = [
        'dilakukan_pada' => 'datetime',
    ];

    // Objek yang divalidasi: Pengajuan / LaporanKemajuan / LaporanHasil
    public function validatable()
    {
        return $this->morphTo();
    }

    // Admin (atau dosen, untuk event kirim_ulang) yang melakukan aksi
    public function admin()
    {
        return $this->belongsTo(Pegawai::class, 'admin_id');
    }

    /**
     * Helper satu baris untuk mencatat riwayat.
     * Dipakai di ValidasiController, dan bisa juga dipanggil dari controller dosen
     * saat kirim ulang revisi: RiwayatValidasi::catat($pengajuan, 'kirim_ulang', null, 'proposal');
     */
    public static function catat(Model $objek, string $status, ?string $catatan = null, ?string $tahap = null, ?int $aktorId = null): self
    {
        return $objek->riwayatValidasi()->create([
            'admin_id'       => $aktorId ?? auth()->id(),
            'tahap'          => $tahap,
            'status'         => $status,
            'catatan'        => $catatan,
            'dilakukan_pada' => now(),
        ]);
    }

    public function labelStatus(): string
    {
        return match ($this->status) {
            'disetujui'   => 'Disetujui',
            'revisi'      => 'Perlu Revisi',
            'kirim_ulang' => 'Dikirim Ulang',
            default       => ucfirst($this->status),
        };
    }
}