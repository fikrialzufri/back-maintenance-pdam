<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelaksanaanPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelaksanaan_pekerjaan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_pelaksanaan_pekerjaan');
            $table->string('slug');
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->longText('keterangan')->nullable();
            $table->longText('keterangan_barang')->nullable();
            $table->longText('lokasi')->nullable();
            $table->string('lat_long')->nullable();
            $table->enum('status', ['diterima', 'proses', 'proses-akhir', 'selesai', 'disetujui', 'dikoreksi', 'selesai koreksi'])->default('diterima');
            $table->enum('tagihan', ['tidak', 'ya'])->default('tidak');
            $table->foreignUuid('aduan_id')->references('id')->on('aduan')->nullable();
            $table->foreignUuid('rekanan_id')->references('id')->on('rekanan')->nullable();
            $table->foreignUuid('penunjukan_pekerjaan_id')->references('id')->on('penunjukan_pekerjaan')->nullable();
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
        Schema::dropIfExists('pelaksanaan_pekerjaans');
    }
}
