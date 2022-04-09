<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAduanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aduan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_ticket');
            $table->string('no_aduan');
            $table->string('mps');
            $table->string('atas_nama');
            $table->string('sumber_informasi');
            $table->longText('body');
            $table->longText('lokasi');
            $table->string('lat_long');
            $table->string('status');
            $table->string('file', 2048)->nullable();
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->string('slug');
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
        Schema::dropIfExists('aduan');
    }
}
