<?php

// app/Models/DetailPenjualan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produk;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'harga_jual',
        'jumlah',
        'sub_total'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

}

