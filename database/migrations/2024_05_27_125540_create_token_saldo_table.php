<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokenSaldoTable extends Migration
{
    public function up()
    {
        Schema::create('token_saldos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->foreign('id_mahasiswa')->references('id')->on('pelanggans')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('amount');
            $table->string('token', 100)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('token_saldos');
    }
}
