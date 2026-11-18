<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunAset extends Model
{
    protected $table = 'akun_aset';

    public const KELOMPOK_AKUN = 'akun';
    public const KELOMPOK_KELOMPOK = 'kelompok';
    public const KELOMPOK_JENIS = 'jenis';
    public const KELOMPOK_OBJEK = 'objek';
    public const KELOMPOK_RINCIAN_OBJEK = 'rincian objek';
    public const KELOMPOK_SUB_RINCIAN_OBJEK = 'sub rincian objek';

    public const DAFTAR_KELOMPOK = [
        self::KELOMPOK_AKUN,
        self::KELOMPOK_KELOMPOK,
        self::KELOMPOK_JENIS,
        self::KELOMPOK_OBJEK,
        self::KELOMPOK_RINCIAN_OBJEK,
        self::KELOMPOK_SUB_RINCIAN_OBJEK,
    ];

    protected $fillable = [
        'kode',
        'nama_aset',
        'akronim',
        'kelompok',
    ];
}
