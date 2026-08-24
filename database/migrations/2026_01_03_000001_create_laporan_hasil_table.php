<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->string('file_nama_asli')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->enum('status', ['proses', 'disetujui', 'revisi'])->default('proses');
            $table->text('catatan_validator')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_hasil');
    }
};
