<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tm_user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'user', 'kasir'])->default('user'); // Bisa admin atau user
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tm_user');
    }
};

