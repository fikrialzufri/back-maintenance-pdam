<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawansTokosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawans_tokos', function (Blueprint $table) {
            $table->foreignUuid('karyawan_id')->nullable()->references('id')->on('karyawan')->onDelete('cascade');
            $table->foreignUuid('toko_id')->nullable()->references('id')->on('toko')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawans_tokos');
    }
}
