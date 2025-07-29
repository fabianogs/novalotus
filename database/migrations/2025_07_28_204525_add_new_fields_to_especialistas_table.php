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
        Schema::table('especialistas', function (Blueprint $table) {
            $table->string('nome_fantasia')->nullable()->after('nome');
            $table->string('registro')->nullable()->after('conselho');
            $table->string('registro_uf')->nullable()->after('registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('especialistas', function (Blueprint $table) {
            $table->dropColumn(['nome_fantasia', 'registro', 'registro_uf']);
        });
    }
};
