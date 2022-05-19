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
            $table->foreignUuid('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->float('panjang')->default(0);
            $table->float('lebar')->default(0);
            $table->float('dalam')->default(0);
            $table->float('total')->default(0);
            $table->string('keterangan')->nullable();
            $table->enum('harga', ['siang', 'malam'])->default('siang');

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
