<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekanan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('slug');
            $table->string('nama_penangung_jawab');
            $table->string('nik');
            $table->string('no_hp');
            $table->string('singkatan')->nullable();
            $table->string('tdd')->nullable();
            $table->string('url')->nullable();
            $table->longText('alamat');
            $table->enum('pkp', ['tidak', 'ya'])->default('tidak');
            $table->foreignUuid('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('rekanans');
    }
}
