<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeCatatan extends Model
{
    protected $table = 'kode_catatan';
    public $timestamps = false;
    protected $fillable = ['kode_catatan', 'uraian', 'id_kode_level'];

    public function kodeLevel()
    {
        return $this->belongsTo(KodeLevel::class, 'id_kode_level');
    }
}
