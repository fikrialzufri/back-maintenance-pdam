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
            $table->string('no_hp')->nullable();
            $table->string('no_pelanggan')->nullable();
            $table->string('detail_lokasi')->nullable();
            $table->enum('kategori_aduan', ['pipa dinas', 'pipa tersier / skunder'])->nullable();
            $table->string('no_aduan');
            $table->string('mps');
            $table->string('atas_nama');
            $table->string('sumber_informasi');
            $table->longText('keterangan')->nullable();
            $table->longText('lokasi');
            $table->string('lat_long');
            $table->enum('status', ['draft', 'proses', 'selesai', 'disetujui']);
            $table->string('file', 2048)->nullable();
            $table->foreignUuid('wilayah_id')->references('id')->on('wilayah');
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
