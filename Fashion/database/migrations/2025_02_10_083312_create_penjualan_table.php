<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur', 50)->unique();
            $table->date('tgl_faktur');
            $table->decimal('total_bayar', 15, 2);
            $table->decimal('dibayar', 15, 2)->nullable();
            $table->decimal('kembali', 15, 2)->nullable();
            $table->string('size')->nullable();

            // Foreign key ke tabel pelanggan
            $table->unsignedBigInteger('pelanggan_id');
            $table->foreign('pelanggan_id')
                  ->references('id')->on('pelanggan')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
