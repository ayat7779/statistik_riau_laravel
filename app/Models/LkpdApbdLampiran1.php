<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkpdApbdLampiran1 extends Model
{
    protected $table = 'lkpd_apbd_lampiran_1';
    public $timestamps = false;
    protected $fillable = ['tahun_lkpd', 'id_kode_catatan', 'jumlah_anggaran', 'jumlah_realisasi'];

    public function kodeCatatan()
    {
        return $this->belongsTo(KodeCatatan::class, 'id_kode_catatan');
    }
}
