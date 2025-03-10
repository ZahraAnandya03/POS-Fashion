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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelanggan', 50);
            $table->string('nama', 100);
            $table->string('alamat', 200);
            $table->string('no_telp', 50);
            $table->string('email', 50)->nullable();
            $table->string('tipe')->default('terdaftar'); // atau 'umum'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
};
