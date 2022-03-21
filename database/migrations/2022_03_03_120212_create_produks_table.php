<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('slug');
            $table->integer('komisi')->default(0);
            $table->integer('harga_beli')->default(0);
            $table->enum('karyawan', ['tidak', 'ya'])->default('tidak')->nullable();
            $table->enum('inc_stok', ['tidak', 'ya'])->default('tidak')->nullable();
            $table->enum('inc_bahan', ['tidak', 'ya'])->default('tidak')->nullable();
            $table->enum('inc_jual', ['tidak', 'ya'])->default('ya')->nullable();
            $table->integer('stok')->default(0)->nullable();
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
        Schema::dropIfExists('produks');
    }
}
