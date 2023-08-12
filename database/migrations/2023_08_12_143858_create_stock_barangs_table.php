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
        Schema::create('stock_barang', function (Blueprint $table) {
            $table->id();
            $table->string('cabang_id');
            $table->string('gudang_id');
            $table->string('barang_id');
            $table->integer('masuk');
            $table->integer('keluar');
            $table->integer('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_barangs');
    }
};
