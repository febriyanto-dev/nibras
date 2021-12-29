<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstWarna extends Model
{
    protected $table = 'mst_warna';
    protected $primaryKey = 'kode_warna';
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'nama_warna'
    ];
}
