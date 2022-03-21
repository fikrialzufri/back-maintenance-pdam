<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukKategoriHargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_kategori_harga', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->string('kategori_harga_jual_id')->references('id')->on('kategori_harga_jual')->onDelete('cascade');
            $table->integer('harga_jual')->default(0);

            //SETTING THE PRIMARY KEYS
            $table->primary(['produk_id', 'kategori_harga_jual_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_kategori_harga');
    }
}
