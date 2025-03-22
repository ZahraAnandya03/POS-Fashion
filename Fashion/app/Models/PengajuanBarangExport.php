<?php

namespace App\Exports;

use App\Models\PengajuanBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengajuanBarangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PengajuanBarang::select('nama_pengaju', 'nama_barang', 'tanggal_pengajuan', 'qty', 'terpenuhi')->get();
    }

    public function headings(): array
    {
        return ['Nama Pengaju', 'Nama Barang', 'Tanggal Pengajuan', 'Qty', 'Terpenuhi'];
    }
}