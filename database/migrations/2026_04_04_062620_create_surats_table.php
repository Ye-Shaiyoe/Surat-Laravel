<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->enum('jenis', ['nota_dinas', 'surat_dinas', 'surat_keputusan', 'surat_pernyataan', 'surat_keterangan']);
            $table->enum('sifat', ['biasa', 'segera', 'rahasia'])->default('biasa');
            $table->text('tujuan');
            $table->string('file_word');
            $table->string('file_lampiran')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->integer('tahap_sekarang')->default(1); // 1-10
            $table->enum('status', ['proses', 'selesai', 'ditolak'])->default('proses');
            $table->boolean('perlu_follow_up')->default(false);
            $table->text('catatan_follow_up')->nullable();
            $table->timestamp('deadline_sla')->nullable(); // 1 hari kerja dari submit
            $table->timestamps();
        });

        Schema::create('surat_tahapans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained()->onDelete('cascade');
            $table->integer('tahap'); // 1-10
            $table->string('nama_tahap');
            $table->enum('status', ['menunggu', 'proses', 'selesai', 'ditolak'])->default('menunggu');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tahapans');
        Schema::dropIfExists('surats');
    }
};