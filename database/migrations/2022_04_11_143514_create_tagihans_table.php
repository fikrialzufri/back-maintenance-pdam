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
            $table->string('kode_anggaran')->nullable();
            $table->string('no_faktur_pajak')->nullable();
            $table->string('no_faktur_pajak_image')->nullable();
            $table->enum('no_faktur_pajak_check', ['ya', 'tidak'])->default('ya');
            $table->string('e_billing')->nullable();
            $table->string('e_billing_image')->nullable();
            $table->enum('e_billing_check', ['ya', 'tidak'])->default('ya');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('bukti_pembayaran_image')->nullable();
            $table->enum('bukti_pembayaran_check', ['ya', 'tidak'])->default('ya');
            $table->string('e_spt')->nullable();
            $table->string('e_spt_image')->nullable();
            $table->enum('e_spt_check', ['ya', 'tidak'])->default('ya');
            $table->string('no_kwitansi')->nullable();
            $table->string('no_kwitansi_image')->nullable();
            $table->enum('no_kwitansi_check', ['ya', 'tidak'])->default('ya');

            $table->date('tanggal_tagihan');
            $table->integer('total')->default(0);
            $table->integer('total_bayar')->default(0);
            $table->enum('status', [
                'dikirim',
                'proses',
                'step1',
                'step2',
                'step3',
                'step4',
                'step5',
                'disetujui',
                'dibayar',
                'disetujui asmentu',
                'disetujui mu',
                'disetujui dirum',
                'disetujui dirut',
                'disetujui asmenanggaran',
                'disetujui asmenakuntan',
                'disetujui mankeu',
                'disetujui asmenkas',
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
