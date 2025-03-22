<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;


class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Kategori::truncate();
        Schema::enableForeignKeyConstraints();
        $file = File::get('database/data/kategori.json');
        $data = json_decode($file);
        foreach ($data as $obj) {
            Kategori::create([
                'nama' => $obj->nama, 
            ]);
        }
        // Kategori::factory()->count(5)->create();
    }
}
