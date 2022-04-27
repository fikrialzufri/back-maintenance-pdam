<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelaksanaanPekerjaanUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelaksanaan_user', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->foreignUuid('pelaksanaan_pekerjaan_id')->references('id')->on('pelaksanaan_pekerjaan')->onDelete('cascade');
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('keterangan')->nullable();
            //SETTING THE PRIMARY KEYS
            $table->primary(['pelaksanaan_pekerjaan_id', 'user_id']);
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
        Schema::dropIfExists('pelaksanaan_pekerjaan_user');
    }
}
