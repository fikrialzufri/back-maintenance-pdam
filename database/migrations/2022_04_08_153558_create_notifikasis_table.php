<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug');
            $table->string('body');
            $table->string('modul');
            $table->string('modul_slug');
            $table->enum('status', ['baca', 'belum'])->default('belum');
            $table->string('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('to_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('modul_id');
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
        Schema::dropIfExists('notifikasis');
    }
}
