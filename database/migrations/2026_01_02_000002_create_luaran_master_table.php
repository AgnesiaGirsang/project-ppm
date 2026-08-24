<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('luaran_master', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['penelitian', 'pengabdian']);
            $table->string('nama'); // contoh: "Artikel ilmiah dimuat di jurnal"
            $table->boolean('wajib')->default(false);
            $table->json('opsi')->nullable(); // contoh: ["Nasional","Nasional Terakreditasi","Internasional"]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('luaran_master');
    }
};
