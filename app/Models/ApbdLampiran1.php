<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApbdLampiran1 extends Model
{
    protected $table = 'apbd_lampiran_1';
    public $timestamps = false;
    protected $fillable = ['tahun', 'id_kode_urut', 'jumlah_anggaran', 'jumlah_perubahan', 'id_jenis_apbd'];

    public function kodeUrut()
    {
        return $this->belongsTo(KodeUrut::class, 'id_kode_urut');
    }

    public function jenisApbd()
    {
        return $this->belongsTo(JenisApbd::class, 'id_jenis_apbd');
    }
}
