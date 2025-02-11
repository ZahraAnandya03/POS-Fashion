<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pemasok extends Model
{
    use HasFactory;
    protected $table = 'pemasok';
    protected $fillable = ['nama_pemasok'];
}
