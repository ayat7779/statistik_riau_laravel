<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeUrut13 extends Model
{
    protected $table = 'kode_urut_13';
    public $timestamps = false;
    protected $fillable = ['no_urut', 'uraian', 'akronim', 'id_kode_level'];

    public function kodeLevel()
    {
        return $this->belongsTo(KodeLevel::class, 'id_kode_level');
    }
}
