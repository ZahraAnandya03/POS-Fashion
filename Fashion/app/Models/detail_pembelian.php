<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class detail_pembalian extends Model
{
    use HasFactory;
    protected $table = 'detail_pembelian';
    protected $fillable = ['pembelian_id', 'barang_id', 'harga_beli', 'jumlah', 'sub_total'];
}
