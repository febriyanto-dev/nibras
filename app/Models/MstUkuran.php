<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstUkuran extends Model
{
    protected $table = 'mst_ukuran';
    protected $primaryKey = 'kode_ukuran';
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'ukuran'
    ];
}
