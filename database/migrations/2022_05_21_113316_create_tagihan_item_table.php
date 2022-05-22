<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan_item', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->uuid('id')->primary();
            $table->string('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->string('tagihan_id')->references('id')->on('tagihan')->onDelete('cascade');
            $table->string('no_pekerjaan')->null();
            $table->string('uraian')->null();
            $table->string('master')->null();
            $table->bigInteger('harga_uraian')->default(0);
            $table->bigInteger('harga_master')->default(0);
            $table->integer('urutan')->default(0);
            $table->float('jumlah')->default(0);
            $table->bigInteger('total_uraian')->default(0);
            $table->bigInteger('total_master')->default(0);
            $table->bigInteger('grand_total')->default(0);
            $table->bigInteger('total_adjust')->default(0);
            $table->bigInteger('grand_total_adjust')->default(0);
            $table->enum('selisih', ['tidak', 'ya'])->default('tidak');
            $table->dateTime('tanggal_adjust')->nullable();
            //SETTING THE PRIMARY KEYS
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
        Schema::dropIfExists('tagihan_item');
    }
}
