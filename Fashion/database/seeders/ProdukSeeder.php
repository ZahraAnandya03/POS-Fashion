<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Produk::truncate();
        Schema::enableForeignKeyConstraints();
        $file = File::get('database/data/produk.json');
        $data = json_decode($file);
        foreach ($data as $obj) {
            Produk::create([
                'nama' => $obj->nama, 
                'kategori_id' => $obj->kategori_id, 
                'pemasok_id' => $obj->pemasok_id, 
                'harga' => $obj->harga, 
                'stok' => $obj->stok, 
                'harga_beli' => $obj->harga_beli, 
                'size' => $obj->size, 
                'gambar' => $obj->gambar, 
            ]);
        }
    }
}
