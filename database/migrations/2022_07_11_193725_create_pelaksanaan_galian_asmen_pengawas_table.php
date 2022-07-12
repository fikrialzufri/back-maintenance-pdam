<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelaksanaanGalianAsmenPengawasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galian_asmen_pengawas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('galian_id')->references('id')->on('galian_pekerjaan')->onDelete('cascade');
            $table->foreignUuid('item_id')->references('id')->on('item')->onDelete('cascade');

            $table->float('panjang')->default(0);
            $table->float('lebar')->default(0);
            $table->float('dalam')->default(0);
            $table->float('harga_satuan', 20)->default(0);
            $table->float('total', 20)->default(0);
            $table->string('keterangan')->nullable();
            $table->enum('harga', ['siang', 'malam'])->default('siang');

            $table->string('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('galian_asmen_pengawas');
    }
}
