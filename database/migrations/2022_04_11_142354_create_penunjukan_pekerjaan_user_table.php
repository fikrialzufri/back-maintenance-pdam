<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenunjukanPekerjaanUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penunjukan_user', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('penunjukan_pekerjaan_id')->references('id')->on('penunjukan_pekerjaan')->onDelete('cascade');
            $table->string('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('keterangan')->nullable();

            //SETTING THE PRIMARY KEYS
            $table->primary(['penunjukan_pekerjaan_id', 'user_id']);
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
        Schema::dropIfExists('penunjukan_pekerjaan_user');
    }
}
