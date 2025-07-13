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
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            
            $table->string('celular')->nullable();
            $table->string('fone1')->nullable();
            $table->string('fone2')->nullable();
            $table->string('email')->nullable();
            $table->string('endereco')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('maps')->nullable();
            $table->string('form_email_to')->nullable();
            $table->string('form_email_cc')->nullable();
            $table->integer('email_port')->nullable();
            $table->string('email_username')->nullable();
            $table->string('email_password')->nullable();
            $table->string('email_host')->nullable();
            $table->text('texto_contrato')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('expediente')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('arquivo_lgpd')->nullable();
            $table->text('texto_lgpd')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
