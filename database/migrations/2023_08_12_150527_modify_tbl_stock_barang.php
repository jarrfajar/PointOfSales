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
        Schema::table('stock_barang', function (Blueprint $table) {
            $table->renameColumn('barang_id','kode_barang');
        });

        Schema::table('stock_barang', function (Blueprint $table) {
            $table->string('kode_barang')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_barang', function (Blueprint $table) {
            //
        });
    }
};
