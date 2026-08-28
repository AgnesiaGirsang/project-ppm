<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_luaran', function (Blueprint $table) {
            $table->boolean('is_wajib')->default(false)->after('opsi_dipilih');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_luaran', function (Blueprint $table) {
            $table->dropColumn('is_wajib');
        });
    }
};