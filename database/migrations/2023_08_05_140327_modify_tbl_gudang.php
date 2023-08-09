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
        Schema::table('barang', function (Blueprint $table) {
            $table->string('deskripsi');
            $table->integer('satuan_id');
            $table->string('supplier');
            $table->date('tanggal_beli');
            $table->date('tanggal_kadaluarsa');
            $table->string('nomor_faktur');
            $table->string('catatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gudang', function (Blueprint $table) {
            //
        });
    }
};
