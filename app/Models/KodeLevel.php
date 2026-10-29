<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeLevel extends Model
{
    protected $table = 'kode_level';
    public $timestamps = false;
    protected $fillable = ['nama_level', 'akronim'];
}
