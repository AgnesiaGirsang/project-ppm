<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun_anggaran')->nullable()->after('tahun');
            $table->unsignedSmallInteger('tahun_pengajuan')->nullable()->after('tahun_anggaran');
            $table->enum('tahun_pelaksanaan', ['I', 'II', 'III'])->nullable()->after('tahun_pengajuan');
            $table->unsignedSmallInteger('tahun_capaian')->nullable()->after('tahun_pelaksanaan');
        });

        // Pindahkan data tahun lama (kalau sudah ada isinya) ke kolom-kolom baru
        DB::table('pengajuan')->update([
            'tahun_anggaran' => DB::raw('tahun'),
            'tahun_pengajuan' => DB::raw('tahun'),
            'tahun_capaian' => DB::raw('tahun'),
        ]);

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun')->nullable();
        });

        DB::table('pengajuan')->update([
            'tahun' => DB::raw('tahun_pengajuan'),
        ]);

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn(['tahun_anggaran', 'tahun_pengajuan', 'tahun_pelaksanaan', 'tahun_capaian']);
        });
    }
};
