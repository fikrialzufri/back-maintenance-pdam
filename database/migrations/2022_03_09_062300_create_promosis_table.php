<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromosisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promosi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('slug');
            $table->date('mulai')->nullable();
            $table->date('selesai')->nullable();
            $table->string('keterangan')->nullable();;

            $table->enum('jenis_diskon', ['nota', 'produk'])->default('produk');
            $table->integer('nota')->nullable();

            $table->enum('type_diskon', ['persen', 'nominal'])->default('nominal');
            $table->integer('diskon')->nullable();

            $table->enum('produk', ['produk', 'semua'])->default('semua');
            $table->string('user_id')->references('id')->on('user')->onDelete('cascade');
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
        Schema::dropIfExists('promsis');
    }
}
