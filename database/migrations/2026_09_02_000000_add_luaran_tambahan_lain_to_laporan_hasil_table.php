<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('laporan_hasil', 'luaran_tambahan_lain')) {
            Schema::table('laporan_hasil', function (Blueprint $table) {
                $table->json('luaran_tambahan_lain')
                    ->nullable()
                    ->after('luaran_tercapai');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('laporan_hasil', 'luaran_tambahan_lain')) {
            Schema::table('laporan_hasil', function (Blueprint $table) {
                $table->dropColumn('luaran_tambahan_lain');
            });
        }
    }
};