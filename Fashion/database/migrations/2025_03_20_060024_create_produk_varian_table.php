<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produk_varian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id'); 
            $table->string('size'); 
            $table->integer('stok')->default(0);
            $table->decimal('harga', 10, 2); 
            $table->timestamps();
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_varian');
    }
};
