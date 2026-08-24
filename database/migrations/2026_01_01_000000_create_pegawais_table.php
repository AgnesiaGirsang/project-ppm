<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20)->unique();
            $table->string('nama');
            $table->string('password'); // disimpan ter-hash (bcrypt)
            $table->rememberToken();
            $table->enum('role', ['admin', 'dosen'])->default('dosen');
            $table->string('jabatan')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('prodi')->nullable();
            $table->string('email')->nullable();
            $table->string('hp', 20)->nullable();
            $table->string('nidn', 20)->nullable();
            $table->boolean('must_change_password')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
