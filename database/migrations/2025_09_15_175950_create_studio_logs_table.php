<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_studio_logs_table.php
    public function up(): void
    {
        Schema::create('studio_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('studio_id'); // 1 untuk Studio 1, 2 untuk Studio 2
            $table->date('tanggal'); // Cukup tanggalnya saja, tanpa jam
            $table->integer('jumlah_jam'); // Berapa jam studio dipakai
            $table->unsignedBigInteger('total_pendapatan'); // jumlah_jam * harga
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_logs');
    }
};
