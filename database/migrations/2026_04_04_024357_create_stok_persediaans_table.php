<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stok_persediaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->integer('jumlah')->default(0);
            $table->string('satuan')->nullable();
            $table->string('status')->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stok_persediaans');
    }
};