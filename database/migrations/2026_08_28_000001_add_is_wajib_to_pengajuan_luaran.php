<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('pengajuan_luaran', 'is_wajib')) {
            Schema::table('pengajuan_luaran', function (Blueprint $table) {
                $table->boolean('is_wajib')
                    ->default(false)
                    ->after('opsi_dipilih');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pengajuan_luaran', 'is_wajib')) {
            Schema::table('pengajuan_luaran', function (Blueprint $table) {
                $table->dropColumn('is_wajib');
            });
        }
    }
};