<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalianPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galian_pekerjaan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pelaksanaan_pekerjaan_id')->references('id')->on('pelaksanaan_pekerjaan')->onDelete('cascade');
            $table->integer('panjang')->nullable();
            $table->integer('lebar')->nullable();
            $table->integer('dalam')->nullable();
            $table->integer('total')->nullable();
            $table->string('bongkaran')->nullable();
            $table->string('keterangan')->nullable();

            $table->string('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('galian_pekerjaans');
    }
}
