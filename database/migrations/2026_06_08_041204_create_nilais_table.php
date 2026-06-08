<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('set null');
            $table->string('mata_pelajaran');
            $table->string('kode');
            $table->float('h1')->nullable();
            $table->float('h2')->nullable();
            $table->float('h3')->nullable();
            $table->float('h4')->nullable();
            $table->float('h5')->nullable();
            $table->float('h6')->nullable();
            $table->float('h7')->nullable();
            $table->float('uts')->nullable();
            $table->float('uas')->nullable();
            $table->float('nilai_akhir')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
