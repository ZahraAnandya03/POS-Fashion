<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukVarian extends Model
{
    protected $table = 'produk_varian';
    protected $fillable = ['produk_id', 'size', 'stok'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}

