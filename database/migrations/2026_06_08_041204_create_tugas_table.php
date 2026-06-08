<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('set null');
            $table->string('judul');
            $table->string('mata_pelajaran');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_pemberian');
            $table->date('tenggat');
            $table->enum('status', ['Aktif', 'Selesai', 'Ditutup'])->default('Aktif');
            $table->string('kelas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
