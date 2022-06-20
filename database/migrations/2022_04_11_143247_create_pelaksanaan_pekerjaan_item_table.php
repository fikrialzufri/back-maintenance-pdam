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
        Schema::create('pelaksanaan_item', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->foreignUuid('pelaksanaan_pekerjaan_id')->references('id')->on('pelaksanaan_pekerjaan')->onDelete('cascade');
            $table->foreignUuid('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->float('qty');
            $table->float('harga');
            $table->float('qty_pengawas')->nullable();
            $table->float('harga_perencanaan')->nullable();
            $table->float('harga_perencanaan_adjust')->nullable();
            $table->float('total')->default(0);
            $table->string('keterangan')->nullable();
            $table->string('keterangan_pengawas')->nullable();
            $table->string('keterangan_perencanaan')->nullable();
            $table->string('keterangan_perencanaan_adjust')->nullable();
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
