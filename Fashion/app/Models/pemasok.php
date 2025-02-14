<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $table = 'pemasok';

    protected $fillable = [
        'nama_pemasok',
        'nomor_telepon',
        'email',
        'alamat',
        'nama_kontak',
        'catatan',
    ];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'pemasok_id');
    }
    
}


