<?php

namespace Database\Seeders;

use App\Models\Pemasok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class PemasokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Pemasok::truncate();
        Schema::enableForeignKeyConstraints();
        $file = File::get('database/data/Pemasok.json');
        $data = json_decode($file);
        foreach ($data as $obj) {
            Pemasok::create([
                'nama_pemasok' => $obj->nama_pemasok, 
                'nomor_telepon' => $obj->nomor_telepon, 
                'email' => $obj->email, 
                'alamat' => $obj->alamat, 
                'nama_kontak' => $obj->nama_kontak, 
                'catatan' => $obj->catatan, 
            ]);
        }
    }
}
