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
        Schema::create('data_rekap_dan_studis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_semester_id')->constrained('data_semester')->onDelete('cascade');
            $table->string('foto_kartu_ujian',260)->nullable();
            $table->string('foto_kartu_studi',260)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rekap_dan_studis');
    }
};
