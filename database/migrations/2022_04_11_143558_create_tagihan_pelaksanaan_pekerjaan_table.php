<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanPelaksanaanPekerjaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan_pelaksanaan', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('tagihan_id')->references('id')->on('tagihan')->onDelete('cascade');
            $table->string('pelaksanaan_pekerjaan_id')->references('id')->on('pelaksanaan_pekerjaan')->onDelete('cascade');
            $table->integer('total')->default(0);
            //SETTING THE PRIMARY KEYS
            $table->primary(['tagihan_id', 'pelaksanaan_pekerjaan_id']);
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
        Schema::dropIfExists('tagihan_pelaksanaan_pekerjaan');
    }
}
