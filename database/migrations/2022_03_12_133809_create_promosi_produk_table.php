<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromosiProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promosi_produk', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->string('promosi_id')->references('id')->on('promosi')->onDelete('cascade');
            $table->integer('diskon')->default(0);
            $table->enum('type_diskon', ['persen', 'nominal'])->default('nominal');

            //SETTING THE PRIMARY KEYS
            $table->primary(['produk_id', 'promosi_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promosi_produk');
    }
}
