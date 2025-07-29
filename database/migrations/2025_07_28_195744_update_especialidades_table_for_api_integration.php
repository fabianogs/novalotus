<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('especialidades', function (Blueprint $table) {
            // Adicionar coluna descricao
            $table->string('descricao')->nullable()->after('nome');
            
            // Adicionar coluna id_api para armazenar o ID da API
            $table->integer('id_api')->nullable()->after('id');
        });

        // Copiar dados da coluna nome para descricao
        DB::statement('UPDATE especialidades SET descricao = nome WHERE descricao IS NULL');

        // Remover coluna nome
        Schema::table('especialidades', function (Blueprint $table) {
            $table->dropColumn('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('especialidades', function (Blueprint $table) {
            // Adicionar coluna nome de volta
            $table->string('nome')->after('id');
            
            // Copiar dados da coluna descricao para nome
            DB::statement('UPDATE especialidades SET nome = descricao WHERE nome IS NULL');
            
            // Remover colunas adicionadas
            $table->dropColumn(['descricao', 'id_api']);
        });
    }
};
