<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalianPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galian_pekerjaan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pelaksanaan_pekerjaan_id')->references('id')->on('pelaksanaan_pekerjaan')->onDelete('cascade');
            $table->foreignUuid('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->float('panjang')->default(0);
            $table->float('lebar')->default(0);
            $table->float('dalam')->default(0);
            $table->float('total')->default(0);
            $table->float('harga_satuan')->default(0);
            $table->float('qty_pengawas')->nullable();
            $table->float('qty_perencanaan_adjust')->nullable();
            $table->float('harga_perencanaan')->nullable();
            $table->float('harga_perencanaan_adjust')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('keterangan_pengawas')->nullable();
            $table->string('keterangan_perencanaan')->nullable();
            $table->string('keterangan_perencanaan_adjust')->nullable();
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
        Schema::dropIfExists('galian_pekerjaans');
    }
}
