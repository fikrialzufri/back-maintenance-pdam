<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_tagihan');
            $table->string('slug');
            $table->string('nomor_bap')->nullable();
            $table->string('kode_vocher')->nullable();
            $table->date('tanggal_tagihan');
            $table->integer('total')->default(0);
            $table->enum('status', [
                'dikirim',
                'proses',
                'step1',
                'step2',
                'step3',
                'step4',
                'step5',
                'disetujui',
                'dibayar'
            ])->default('dikirim');
            $table->foreignUuid('rekanan_id')->references('id')->on('rekanan')->nullable();
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
        Schema::dropIfExists('tagihans');
    }
}
