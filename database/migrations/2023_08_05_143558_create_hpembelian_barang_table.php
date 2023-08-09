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
        Schema::create('hpurchase_order', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cabang');
            $table->string('nomor_purchase_order');
            $table->integer('gudang_id');
            $table->dateTime('tanggal');
            $table->integer('supplier_id');
            $table->decimal('total_harga', 18);
            $table->string('deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hpembelian_barang');
    }
};
