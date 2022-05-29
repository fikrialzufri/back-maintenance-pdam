<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenunjukanPekerjaanTable extends Migration
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
            $table->enum('status', ['draft', 'proses', 'selesai', 'disetujui', 'dikoreksi', 'selesai koreksi'])->default('draft');
            $table->enum('kategori_aduan', ['pipa dinas', 'pipa tersier / skunder'])->nullable();
            $table->enum('tagihan', ['tidak', 'ya'])->default('tidak');
            $table->foreignUuid('aduan_id')->references('id')->on('aduan');
            $table->foreignUuid('rekanan_id')->references('id')->on('rekanan');
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
        Schema::dropIfExists('penunjukan_pekerjaan');
    }
}
