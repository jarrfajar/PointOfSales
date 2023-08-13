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
        Schema::create('hretur_penerimaan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_retur');
            $table->string('nomor_bapb');
            $table->date('tanggal');
            $table->integer('supplier_id');
            $table->integer('gudang_id');
            $table->string('nomor_resi');
            $table->string('deskripsi');
            $table->decimal('sub_total', 18);
            $table->decimal('diskon', 18);
            $table->decimal('ppn', 18);
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
        Schema::dropIfExists('header_retur_penerimaan_barangs');
    }
};
