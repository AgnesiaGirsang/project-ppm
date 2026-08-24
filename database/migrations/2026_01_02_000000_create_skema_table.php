<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skema', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['penelitian', 'pengabdian']);
            $table->enum('jalur', ['simlitabkes', 'mandiri']);
            $table->string('nama'); // contoh: "Penelitian Kerja Sama Antar Perguruan Tinggi (PKPT)"
            $table->string('kode', 20)->nullable(); // contoh: "PKPT"
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skema');
    }
};
