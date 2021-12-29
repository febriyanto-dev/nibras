<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk_';
    protected $primaryKey = 'kode_barang';
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'nama_barang',
        'kode_ukuran',
        'kode_warna',
        'harga',
        'harga_dasar'
    ];
}
