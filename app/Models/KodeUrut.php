<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeUrut extends Model
{
    protected $table = 'kode_urut';
    public $timestamps = false;
    protected $fillable = ['no_urut', 'uraian', 'akronim', 'id_kode_level'];

    public function kodeLevel()
    {
        return $this->belongsTo(KodeLevel::class, 'id_kode_level');
    }
}
