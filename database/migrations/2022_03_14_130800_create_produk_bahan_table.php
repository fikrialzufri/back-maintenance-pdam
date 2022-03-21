<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukBahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_bahan', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->string('bahan_id')->references('id')->on('produk')->onDelete('cascade');
            $table->integer('qty')->default(0);

            //SETTING THE PRIMARY KEYS
            $table->primary(['produk_id', 'bahan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_bahan');
    }
}
