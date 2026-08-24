<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('luaran_realisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_luaran_id')->constrained('pengajuan_luaran')->cascadeOnDelete();
            $table->text('keterangan')->nullable(); // deskripsi capaian, misal judul artikel/nama HKI
            $table->string('link_bukti')->nullable(); // link jurnal/dokumen online
            $table->string('file_path')->nullable();
            $table->string('file_nama_asli')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->date('tanggal_realisasi')->nullable();
            $table->enum('status', ['proses', 'disetujui', 'revisi'])->default('proses');
            $table->text('catatan_validator')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('luaran_realisasi');
    }
};
