<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan_tim', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();

            // Anggota dari sistem (dosen terdaftar) - nullable karena anggota bisa juga dari luar sistem
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->cascadeOnDelete();

            // Anggota dari luar sistem (belum punya akun SIPPM)
            $table->string('nama_luar')->nullable();
            $table->string('instansi_luar')->nullable();

            $table->enum('peran', ['ketua', 'anggota'])->default('anggota');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_tim');
    }
};
