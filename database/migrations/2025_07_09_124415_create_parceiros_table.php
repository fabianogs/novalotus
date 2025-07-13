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
        Schema::create('parceiros', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('logo_carrossel')->nullable();
            $table->string('nome');
            $table->text('descricao')->nullable();
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
        Schema::dropIfExists('parceiros');
    }
};
