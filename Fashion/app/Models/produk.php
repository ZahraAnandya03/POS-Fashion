<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = ['nama', 'harga', 'stok', 'size', 'gambar', 'kategori_id', 'pemasok_id']; 

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class, 'produk_id');
    }
    
}

