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
        Schema::create('hpenjualan', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->dateTime('tanggal');
            $table->integer('kasir_id');
            $table->integer('member_id');
            $table->integer('point');
            $table->integer('jumlah_point');
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
        Schema::dropIfExists('hpenjualan');
    }
};
