<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenunjukanPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penunjukan_pekerjaan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_pekerjaan');
            $table->string('slug');
            $table->enum('status', ['draft', 'proses', 'selesai', 'disetujui']);
            $table->foreignUuid('rekanan_id')->references('id')->on('rekanan')->nullable();
            $table->foreignUuid('aduan_id')->references('id')->on('aduan')->nullable();
            $table->foreignUuid('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('penunjukan_pekerjaans');
    }
}
