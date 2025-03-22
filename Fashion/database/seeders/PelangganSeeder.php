<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;


class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Pelanggan::truncate();
        Schema::enableForeignKeyConstraints();
        $file = File::get('database/data/pelanggan.json');
        $data = json_decode($file);
        foreach ($data as $obj) {
            Pelanggan::create([
                'kode_pelanggan' => $obj->kode_pelanggan, 
                'nama' => $obj->nama, 
                'alamat' => $obj->alamat, 
                'no_telp' => $obj->no_telp, 
                'email' => $obj->email, 
                'tipe' => $obj->tipe, 
            ]);
        }
    }
}
