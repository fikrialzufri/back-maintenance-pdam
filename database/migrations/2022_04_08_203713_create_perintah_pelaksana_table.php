<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerintahPelaksanaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perintah_pelaksana', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rekanan_id')->references('id')->on('rekanan')->nullable();
            $table->foreignUuid('aduan_id')->references('id')->on('aduan')->nullable();
            $table->foreignUuid('manager_id')->references('id')->on('users')->nullable();
            $table->foreignUuid('asisten_manager_id')->references('id')->on('users')->nullable();
            $table->string('dikeluarkan_di')->nullable();
            $table->date('tanggal')->nullable();
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
        Schema::dropIfExists('perintah_pelaksana');
    }
}
