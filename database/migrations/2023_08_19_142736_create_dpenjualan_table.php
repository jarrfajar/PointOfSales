<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dpenjualan', function (Blueprint $table) {
            $table->id();
            $table->integer('hpenjualan');
            $table->integer('barang_id');
            $table->integer('satuan_id');
            $table->decimal('harga', 18);
            $table->decimal('diskon', 18);
            $table->decimal('total', 18);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dpenjualan');
    }
};
