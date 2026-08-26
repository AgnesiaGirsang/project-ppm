<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_hasil', function (Blueprint $table) {
            $table->text('ringkasan_hasil')->nullable()->after('pengajuan_id');
            $table->string('link_inovasi_produk')->nullable()->after('file_size');
            $table->string('no_sk')->nullable()->after('link_inovasi_produk');
            $table->json('luaran_tercapai')->nullable()->after('no_sk');
            $table->json('dokumentasi')->nullable()->after('luaran_tercapai');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_hasil', function (Blueprint $table) {
            $table->dropColumn(['ringkasan_hasil', 'link_inovasi_produk', 'no_sk', 'luaran_tercapai', 'dokumentasi']);
        });
    }
};
