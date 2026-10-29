<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisApbd extends Model
{
    protected $table = 'jenis_apbd';
    public $timestamps = false;
    protected $fillable = ['uraian', 'akronim'];
}
