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
        Schema::create('akun_aset', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 100);
            $table->string('nama_aset', 255);
            $table->decimal('jumlah_realisasi', 20, 2)->default(0);
            $table->string('akronim', 100)->nullable();
            $table->enum('kelompok', [
                'akun',
                'kelompok',
                'jenis',
                'objek',
                'rincian objek',
                'sub rincian objek',
            ])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_aset');
    }
};
