<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_nota', 50);
            $table->string('slug', 50);
            $table->integer('total');
            $table->integer('grandtotal');
            $table->integer('uang_angsul');
            $table->integer('uang_bayar');
            $table->integer('diskon')->default(0)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignUuid('promosi_id')->nullable()->references('id')->on('promosi')->onDelete('cascade');
            $table->foreignUuid('toko_id')->nullable()->references('id')->on('toko')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignUuid('pelanggan_id')->nullable()->references('id')->on('pelanggan')->onDelete('cascade');
            $table->foreignUuid('bank_id')->nullable()->references('id')->on('bank')->onDelete('cascade');
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
        Schema::dropIfExists('penjualans');
    }
}
