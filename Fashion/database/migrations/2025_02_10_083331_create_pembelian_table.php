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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemasok_id')
                ->constrained('pemasok')
                ->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('pembelian');
    }
};
