<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan_user', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('tagihan_id')->references('id')->on('tagihan')->onDelete('cascade');
            $table->string('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('keterangan')->nullable();
            //SETTING THE PRIMARY KEYS
            $table->primary(['tagihan_id', 'user_id']);
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
        Schema::dropIfExists('tagihan_user');
    }
}
