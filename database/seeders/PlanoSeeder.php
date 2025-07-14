<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plano;

class PlanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planos = [
            [
                'titulo' => 'Plano Básico de Saúde',
                'descricao' => 'Plano de saúde com cobertura básica incluindo consultas médicas, exames simples e procedimentos de emergência. Ideal para quem busca proteção essencial com custo acessível.',
                'sintese' => 'Cobertura básica de saúde com consultas e exames essenciais',
                'imagem' => '/images/planos/plano-basico.jpg',
                'link' => 'https://example.com/plano-basico',
                'slug' => 'plano-basico-saude'
            ],
            [
                'titulo' => 'Plano Premium Saúde',
                'descricao' => 'Plano completo de saúde com cobertura ampla incluindo especialistas, cirurgias, internações, exames complexos e tratamentos avançados. Para quem não abre mão da melhor assistência médica.',
                'sintese' => 'Cobertura completa com especialistas e procedimentos avançados',
                'imagem' => '/images/planos/plano-premium.jpg',
                'link' => 'https://example.com/plano-premium',
                'slug' => 'plano-premium-saude'
            ],
            [
                'titulo' => 'Plano Odontológico Completo',
                'descricao' => 'Plano odontológico com cobertura total para tratamentos dentários, incluindo limpeza, restaurações, extrações, implantes e ortodontia. Cuidado completo para sua saúde bucal.',
                'sintese' => 'Cobertura odontológica completa incluindo ortodontia',
                'imagem' => '/images/planos/plano-odonto.jpg',
                'link' => 'https://example.com/plano-odontologico',
                'slug' => 'plano-odontologico-completo'
            ],
            [
                'titulo' => 'Plano Familiar Saúde e Odonto',
                'descricao' => 'Plano especial para famílias que combina cobertura médica e odontológica para todos os membros. Proteção completa com valores especiais para grupos familiares.',
                'sintese' => 'Plano médico e odontológico para toda a família',
                'imagem' => '/images/planos/plano-familiar.jpg',
                'link' => 'https://example.com/plano-familiar',
                'slug' => 'plano-familiar-saude-odonto'
            ],
            [
                'titulo' => 'Plano Executivo Plus',
                'descricao' => 'Plano exclusivo para executivos e empresários com cobertura VIP, incluindo atendimento personalizado, check-ups executivos, segunda opinião médica e acesso a rede internacional.',
                'sintese' => 'Plano VIP com atendimento personalizado e cobertura internacional',
                'imagem' => '/images/planos/plano-executivo.jpg',
                'link' => 'https://example.com/plano-executivo',
                'slug' => 'plano-executivo-plus'
            ]
        ];

        foreach ($planos as $plano) {
            Plano::create($plano);
        }
    }
}
