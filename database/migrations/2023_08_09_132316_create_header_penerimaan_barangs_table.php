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
        Schema::create('hpenerimaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cabang');
            $table->string('nomor_bapb');
            $table->string('nomor_faktur');
            $table->string('nomor_purchase_order');
            $table->string('nomor_resi');
            $table->integer('gudang_id');
            $table->integer('supplier_id');
            $table->date('tanggal_po');
            $table->date('tanggal_terima');
            $table->date('tanggal_tempo');
            $table->string('deskripsi');
            $table->decimal('total_harga', 18);
            $table->decimal('sub_total', 18);
            $table->decimal('diskon', 18);
            $table->decimal('ppn', 18);
            $table->decimal('total_hargaar', 18);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('header_penerimaan_barangs');
    }
};
