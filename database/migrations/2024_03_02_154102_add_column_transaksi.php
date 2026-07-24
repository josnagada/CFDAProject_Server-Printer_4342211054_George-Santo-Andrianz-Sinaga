<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_transaksi',['tambah_saldo','mahasiswa_print'])->default('mahasiswa_print');
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->foreign('id_pelanggan')->references('id')->on('pelanggans')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->foreign('id_karyawan')->references('id')->on('karyawans')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_file')->nullable();
            $table->foreign('id_file')->references('id')->on('files')->onDelete('cascade')->onUpdate('cascade');
            $table->string('harga')->nullable();
            $table->string('jumlah_saldo_yang_ditambahkan')->nullable();
            $table->string('token', 100)->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
