<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->uuid('user_id');
            $table->uuid('jenis_surat_id');
            $table->dateTime('tanggal');
            $table->string('kode_depan')->nullable();
            $table->string('kode_belakang')->nullable();
            $table->string('urutan')->nullable();
            $table->string('nomor')->unique();
            $table->string('perihal');
            $table->string('file_type')->nullable();
            $table->string('file')->nullable();
            $table->json('opt')->nullable();
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
        Schema::dropIfExists('surats');
    }
}
