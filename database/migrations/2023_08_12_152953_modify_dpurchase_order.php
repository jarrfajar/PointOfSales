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
        Schema::table('dpurchase_order', function (Blueprint $table) {
            $table->renameColumn('kode_barang','barang_id');
        });

        Schema::table('dpurchase_order', function (Blueprint $table) {
            $table->integer('barang_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dpurchase_order', function (Blueprint $table) {
            //
        });
    }
};
