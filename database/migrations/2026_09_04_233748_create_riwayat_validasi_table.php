<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_validasi', function (Blueprint $table) {
            $table->id();

            // Polymorphic: bisa nempel ke Pengajuan, LaporanKemajuan, atau LaporanHasil
            $table->string('validatable_type');
            $table->unsignedBigInteger('validatable_id');

            // Admin yang melakukan aksi (nullable: kalau akun admin dihapus, riwayat tetap ada)
            $table->foreignId('admin_id')->nullable()->constrained('pegawais')->nullOnDelete();

            // proposal | laporan_kemajuan | laporan_hasil
            $table->string('tahap', 30)->nullable();

            // disetujui | revisi | kirim_ulang (opsional, dari sisi dosen)
            $table->string('status', 30);

            $table->text('catatan')->nullable();
            $table->timestamp('dilakukan_pada');

            $table->timestamps();

            $table->index(['validatable_type', 'validatable_id'], 'riwayat_validasi_morph_idx');
        });

        // ==========================================================
        // BACKFILL: pindahkan data validasi lama (kolom divalidasi_oleh /
        // divalidasi_pada / catatan_validator) ke tabel riwayat supaya
        // pengajuan yang sudah pernah divalidasi tidak kosong riwayatnya.
        // ==========================================================
        $sumber = [
            ['tabel' => 'pengajuan',        'model' => 'App\\Models\\Pengajuan',        'tahap' => 'proposal'],
            ['tabel' => 'laporan_kemajuan', 'model' => 'App\\Models\\LaporanKemajuan', 'tahap' => 'laporan_kemajuan'],
            ['tabel' => 'laporan_hasil',    'model' => 'App\\Models\\LaporanHasil',    'tahap' => 'laporan_hasil'],
        ];

        foreach ($sumber as $s) {
            if (!Schema::hasTable($s['tabel']) || !Schema::hasColumn($s['tabel'], 'divalidasi_oleh')) {
                continue;
            }

            $adaKolomWaktu = Schema::hasColumn($s['tabel'], 'divalidasi_pada');

            $rows = DB::table($s['tabel'])->whereNotNull('divalidasi_oleh')->get();

            foreach ($rows as $row) {
                // Kalau status sekarang 'proses' padahal pernah divalidasi,
                // berarti keputusan terakhir admin adalah 'revisi' lalu dosen kirim ulang.
                $status = in_array($row->status, ['disetujui', 'revisi']) ? $row->status : 'revisi';

                $waktu = ($adaKolomWaktu && $row->divalidasi_pada)
                    ? $row->divalidasi_pada
                    : ($row->updated_at ?? now());

                DB::table('riwayat_validasi')->insert([
                    'validatable_type' => $s['model'],
                    'validatable_id'   => $row->id,
                    'admin_id'         => $row->divalidasi_oleh,
                    'tahap'            => $s['tahap'],
                    'status'           => $status,
                    'catatan'          => $row->catatan_validator ?? null,
                    'dilakukan_pada'   => $waktu,
                    'created_at'       => $waktu,
                    'updated_at'       => $waktu,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_validasi');
    }
};