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
        Schema::create('dretur_penerimaan_barang', function (Blueprint $table) {
            $table->id();
            $table->integer('header_retur_id');
            $table->integer('barang_id');
            $table->integer('satuan_id');
            $table->integer('jumlah');
            $table->integer('diskon_persen');
            $table->decimal('diskon_rp', 18);
            $table->integer('ppn');
            $table->decimal('harga', 18);
            $table->decimal('total_harga', 18);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_retur_penerimaan_barangs');
    }
};
