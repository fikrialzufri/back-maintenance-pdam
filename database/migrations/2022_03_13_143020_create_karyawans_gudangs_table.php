<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawansGudangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawans_gudangs', function (Blueprint $table) {
            $table->foreignUuid('karyawan_id')->nullable()->references('id')->on('karyawan')->onDelete('cascade');
            $table->foreignUuid('gudang_id')->nullable()->references('id')->on('gudang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawans_gudangs');
    }
}
