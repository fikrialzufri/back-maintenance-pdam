<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelaksanaanPekerjaanItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelaksanaan_pekerjaan_item', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('pelaksanaan_pekerjaan_id')->references('id')->on('pelaksanaan_pekerjaan')->onDelete('cascade');
            $table->string('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('harga');
            //SETTING THE PRIMARY KEYS
            $table->primary(['pelaksanaan_pekerjaan_id', 'item_id']);
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
        Schema::dropIfExists('pelaksanaan_pekerjaan_item');
    }
}
