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
        Schema::create('especialistas', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nome');
            $table->string('conselho')->nullable();
            $table->foreignId('especialidade_id')->constrained('especialidades')->onDelete('cascade')->nullable();
            $table->foreignId('cidade_id')->constrained('cidades')->onDelete('cascade')->nullable();
            $table->string('endereco')->nullable();
            $table->foreignId('necessidade_id')->constrained('necessidades')->onDelete('cascade')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especialistas');
    }
};
