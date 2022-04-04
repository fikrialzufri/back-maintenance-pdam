<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanRekanan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_rekanan', function (Blueprint $table) {
            $table->string('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            $table->string('rekanan_id')->references('id')->on('rekanan')->onDelete('cascade');
            $table->timestamps();
            //SETTING THE PRIMARY KEYS
            $table->primary(['rekanan_id', 'karyawan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_rekanan');
    }
}
