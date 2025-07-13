<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existe um registro de configuração
        if (Config::count() === 0) {
            Config::create([
                'razao_social' => 'Nova Lotus',
                'cnpj' => null,
                'endereco' => null,
                'expediente' => null,
                'email' => null,
                'celular' => null,
                'fone1' => null,
                'fone2' => null,
                'whatsapp' => null,
                'facebook' => null,
                'instagram' => null,
                'twitter' => null,
                'youtube' => null,
                'linkedin' => null,
                'maps' => null,
                'form_email_to' => null,
                'form_email_cc' => null,
                'email_host' => null,
                'email_port' => null,
                'email_username' => null,
                'email_password' => null,
                'texto_contrato' => null,
                'texto_lgpd' => null,
                'arquivo_lgpd' => null,
            ]);
        }
    }
} 