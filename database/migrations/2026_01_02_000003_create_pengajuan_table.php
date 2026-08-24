<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique(); // contoh: PNL-2026-00125

            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete(); // ketua pengaju
            $table->enum('jenis', ['penelitian', 'pengabdian']);
            $table->enum('jalur', ['simlitabkes', 'mandiri']);
            $table->foreignId('skema_id')->constrained('skema');
            $table->foreignId('rumpun_ilmu_id')->nullable()->constrained('rumpun_ilmu')->nullOnDelete();

            $table->string('judul');
            $table->year('tahun');

            $table->string('proposal_path')->nullable();
            $table->string('proposal_nama_asli')->nullable();
            $table->unsignedBigInteger('proposal_size')->nullable(); // bytes

            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->text('inovasi_produk')->nullable();

            $table->enum('tahap', ['proposal', 'laporan_kemajuan', 'laporan_hasil'])->default('proposal');
            $table->enum('status', ['proses', 'disetujui', 'revisi'])->default('proses');
            $table->text('catatan_validator')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
