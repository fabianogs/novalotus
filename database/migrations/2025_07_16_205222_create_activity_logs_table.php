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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Usuário que executou a ação
            $table->string('action'); // 'create', 'update', 'delete', 'login', 'logout'
            $table->string('model')->nullable(); // Nome do modelo (Sobre, Especialista, etc.)
            $table->unsignedBigInteger('model_id')->nullable(); // ID do registro afetado
            $table->text('description'); // Descrição da ação
            $table->json('old_values')->nullable(); // Valores antigos (para update/delete)
            $table->json('new_values')->nullable(); // Valores novos (para create/update)
            $table->string('ip_address')->nullable(); // IP do usuário
            $table->string('user_agent')->nullable(); // User agent do navegador
            $table->string('url')->nullable(); // URL da requisição
            $table->string('method')->nullable(); // Método HTTP
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['user_id']);
            $table->index(['action']);
            $table->index(['model']);
            $table->index(['created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
