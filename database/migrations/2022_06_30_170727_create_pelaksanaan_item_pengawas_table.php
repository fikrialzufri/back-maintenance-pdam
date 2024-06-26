<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelaksanaanItemPengawasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_pengawas', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->foreignUuid('pelaksanaan_pekerjaan_id')->references('id')->on('pelaksanaan_pekerjaan')->onDelete('cascade');
            $table->foreignUuid('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->float('qty', 20, 3)->default(0);

            $table->float('harga', 20, 3)->default(0);
            $table->float('total', 20, 3)->default(0);
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('item_pengawas');
    }
}
