<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('akun_aset', function (Blueprint $table) {
            $table->dropColumn('jumlah_realisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akun_aset', function (Blueprint $table) {
            $table->decimal('jumlah_realisasi', 20, 2)->default(0);
        });
    }
};
