<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('slug');
            $table->integer('komisi')->default(0);
            $table->integer('target_pendapatan')->default(0);
            $table->integer('bonus_target')->default(0);
            $table->integer('gajih_perbulan')->default(0);
            $table->integer('gajih_perhari')->default(0);
            $table->enum('gajih', ['perbulan', 'perhari'])->default('perbulan');
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
        Schema::dropIfExists('jabatans');
    }
}
