<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('slug');
            $table->integer('harga')->default(0)->nullable();
            $table->integer('harga_malam')->default(0)->nullable();
            $table->enum('hapus', ['tidak', 'ya'])->default('tidak');
            $table->string('jenis_id')->references('id')->on('jenis');
            $table->string('satuan_id')->references('id')->on('satuan');
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
        Schema::dropIfExists('items');
    }
}
