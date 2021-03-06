<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengajuanBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_barangs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pengaju');
            $table->string('nama');
            $table->integer('kuantitas');
            $table->string('keterangan');
            $table->string('status')->default('Belum dikonfirmasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pengajuan_barangs');
    }
}
