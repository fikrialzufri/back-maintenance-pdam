<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('jadwal_id')->nullable()->references('id')->on('jadwals')->onDelete('set null');
            $table->foreignUuid('karyawan_id')->nullable()->references('id')->on('karyawan')->onDelete('set null');
            $table->string('status');
            $table->string('keterangan');
            $table->integer('menit')->default(0);
            $table->integer('denda')->default(0);
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
        Schema::dropIfExists('absensi');
    }
}
