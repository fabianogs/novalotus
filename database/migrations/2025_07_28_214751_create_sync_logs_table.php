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
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity'); // especialidades, cidades, especialistas
            $table->string('status'); // success, error, partial
            $table->integer('total_items')->default(0);
            $table->integer('created_items')->default(0);
            $table->integer('updated_items')->default(0);
            $table->integer('error_items')->default(0);
            $table->text('error_message')->nullable();
            $table->json('details')->nullable(); // Detalhes adicionais da sincronização
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            
            $table->index(['entity', 'status']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
