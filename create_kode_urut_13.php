<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasTable('kode_urut_13')) {
        Schema::create('kode_urut_13', function (Blueprint $table) {
            $table->id();
            $table->string('no_urut', 11)->nullable();
            $table->string('uraian', 255)->nullable();
            $table->string('akronim', 255)->nullable();
            $table->integer('id_kode_level')->nullable();
        });
        echo "Table 'kode_urut_13' created successfully.\n";
    } else {
        echo "Table 'kode_urut_13' already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error creating table kode_urut_13: " . $e->getMessage() . "\n";
}
