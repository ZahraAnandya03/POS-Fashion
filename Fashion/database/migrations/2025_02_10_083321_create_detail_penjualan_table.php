<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade'); 
            $table->double('harga_jual');
            $table->integer('jumlah');
            $table->double('sub_total');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
