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
        Schema::table('hpenerimaan_barangs', function (Blueprint $table) {
            $table->dropColumn('nomor_purchase_order');
        });

        Schema::table('hpenerimaan_barangs', function (Blueprint $table) {
            $table->integer('purchase_order_id')->after('nomor_faktur');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hpenerimaan_barangs', function (Blueprint $table) {
            //
        });
    }
};
