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
        Schema::table('produk', function (Blueprint $table) {
            $table->foreignId('pemasok_id')->nullable()->after('kategori_id')->constrained('pemasok')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['pemasok_id']);
            $table->dropColumn('pemasok_id');
        });
    }

};
