<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_kemajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();

            $table->unsignedTinyInteger('persentase')->default(0); // 0-100

            $table->string('file_path')->nullable(); // dokumen kemajuan (PDF)
            $table->string('file_nama_asli')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->json('dokumentasi')->nullable(); // array foto dokumentasi kegiatan: [{path, nama}]

            $table->text('kegiatan_dilakukan')->nullable();
            $table->text('kendala')->nullable();
            $table->text('rencana_berikutnya')->nullable();

            $table->json('luaran_tercapai')->nullable(); // array id pengajuan_luaran yang dicentang tercapai

            $table->enum('status', ['draft', 'proses', 'disetujui', 'revisi'])->default('draft');
            $table->text('catatan_validator')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kemajuan');
    }
};
