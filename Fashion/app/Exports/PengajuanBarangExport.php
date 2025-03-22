<?php

namespace App\Exports;

use App\Models\Pengajuan;
use App\Models\PengajuanBarang;
use Maatwebsite\Excel\Concerns\FromCollection;

class PengajuanBarangExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PengajuanBarang::all();
    }
}
