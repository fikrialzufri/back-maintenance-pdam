<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelaksanaanAdjustTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelaksanaan_adjust', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pelaksanaan_pekerjaan_id')->references('id')->on('pelaksanaan_pekerjaan')->onDelete('cascade');
            $table->foreignUuid('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('qty')->default(0);
            $table->integer('harga')->default(0);
            $table->integer('total')->default(0);
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('pelaksanaan_adjust');
    }
}
