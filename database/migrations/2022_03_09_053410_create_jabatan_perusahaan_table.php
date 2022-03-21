<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanPerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_perusahaan', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('modul_id');
            $table->string('jabatan_id')->references('id')->on('jabatan')->onDelete('cascade');
            $table->string('modul');

            //SETTING THE PRIMARY KEYS
            $table->primary(['modul_id', 'jabatan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan_perusahaan');
    }
}
