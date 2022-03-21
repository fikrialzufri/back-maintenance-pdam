<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_produk', function (Blueprint $table) {
            $table->integer('harga_beli')->default(0);
            $table->integer('harga_jual')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('total_harga')->default(0);
            $table->integer('komisi')->default(0);
            $table->integer('diskon_produk')->default(0);
            $table->string('type_diskon')->nullable();

            $table->foreignUuid('promosi_id')->nullable()->references('id')->on('promosi')->onDelete('cascade');
            $table->foreignUuid('karyawan_id')->nullable()->references('id')->on('karyawan')->onDelete('cascade');

            //FOREIGN KEY CONSTRAINTS
            $table->string('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->string('penjualan_id')->references('id')->on('penjualan')->onDelete('cascade');

            $table->timestamps();
            //SETTING THE PRIMARY KEYS
            $table->primary(['produk_id', 'penjualan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_produk');
    }
}
