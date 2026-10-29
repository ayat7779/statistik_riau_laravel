<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$levels = DB::select("SELECT id, nama_level, akronim FROM kode_level ORDER BY id");
foreach($levels as $l) {
    echo "ID: {$l->id} | Nama: {$l->nama_level} | Akronim: {$l->akronim}\n";
}
print_r(DB::select("SELECT DISTINCT b.id_kode_level, c.nama_level FROM apbd_lampiran_1 a JOIN kode_urut b ON a.id_kode_urut = b.id JOIN kode_level c ON b.id_kode_level = c.id"));
