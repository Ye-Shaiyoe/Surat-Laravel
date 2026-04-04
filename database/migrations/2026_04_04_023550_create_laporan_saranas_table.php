<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_saranas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('kategori');
            $table->string('nama_aset');
            $table->text('deskripsi');
            $table->enum('prioritas', ['rendah','sedang','tinggi']);
            $table->date('tanggal_diperlukan')->nullable();
            $table->string('foto')->nullable();
            $table->string('status')->default('diproses');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_saranas');
    }
};