<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemasok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemasok');
            $table->string('nomor_telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_kontak')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemasok');
    }
};
