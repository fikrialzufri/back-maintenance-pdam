<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts_jabatans', function (Blueprint $table) {
            $table->foreignUuid('shift_id')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreignUuid('jabatan_id')->references('id')->on('jabatan')->onDelete('cascade');
            $table->integer('denda')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts_jabatans');
    }
}
