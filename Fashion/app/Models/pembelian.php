<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $fillable = ['pemasok_id', 'tanggal', 'total_harga'];

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailPembelian::class);
    }
}
