<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('hari');
            $table->string('mapel');
            $table->string('jam_mulai');
            $table->string('jam_selesai');
            $table->string('guru');
            $table->string('kelas');
            $table->string('ruangan');
            $table->string('warna')->nullable();
            $table->string('jenis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
