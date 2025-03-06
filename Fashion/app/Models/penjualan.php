<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan;
use App\Models\DetailPenjualan;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'no_faktur',
        'tgl_faktur',
        'total_bayar',
        'dibayar',
        'kembali',
        'pelanggan_id',
        'size', 
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }
}
