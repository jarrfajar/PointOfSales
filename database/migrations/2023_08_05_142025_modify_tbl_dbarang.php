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
        Schema::table('dbarang', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
            $table->dropColumn('catatan');
            $table->dropColumn('nomor_faktur');
            $table->dropColumn('tanggal_beli');
            $table->dropColumn('supplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dbarang', function (Blueprint $table) {
            //
        });
    }
};
