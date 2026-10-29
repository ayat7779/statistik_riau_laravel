<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = DB::select("
    SELECT a.tahun, b.no_urut, b.uraian, c.nama_level, a.jumlah_anggaran
    FROM apbd_lampiran_1 a
    JOIN kode_urut b ON a.id_kode_urut = b.id
    JOIN kode_level c ON b.id_kode_level = c.id
    LIMIT 10
");

foreach($rows as $r) {
    echo "{$r->tahun} | {$r->no_urut} | {$r->nama_level} | {$r->uraian} | {$r->jumlah_anggaran}\n";
}
