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
            $table->enum('status', 
            [
                'draft', 
                'proses', 
                'selesai', 
                'approve', 
                'disetujui', 
                'koreksi pengawas', 
                'dikoreksi', 
                'selesai koreksi', 
                'diadjust'
            ]
            )->default('draft');
            $table->enum('kategori_aduan', ['pipa dinas', 'pipa premier / skunder'])->nullable();
            $table->enum('tagihan', ['tidak', 'ya'])->default('tidak');
            $table->foreignUuid('aduan_id')->references('id')->on('aduan');
            $table->foreignUuid('rekanan_id')->nullable()->references('id')->on('rekanan');
            $table->foreignUuid('karyawan_id')->nullable()->references('id')->on('karyawan');
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
