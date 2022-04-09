<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAduanJenisAduanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aduan_jenis_aduan', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('aduan_id')->references('id')->on('aduan')->onDelete('cascade');
            $table->string('jenis_aduan_id')->references('id')->on('jenis_aduan')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['aduan_id', 'jenis_aduan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aduan_jenis_aduan');
    }
}
