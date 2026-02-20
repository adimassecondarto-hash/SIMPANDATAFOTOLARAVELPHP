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
        Schema::create('data_semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('login_id')->constrained('logins')->onDelete('cascade');
            $table->string('nama',200);
            $table->string('npm',200);
            $table->string('foto_semester',200)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_semester');
    }
};
