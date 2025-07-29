<?php

namespace App\Console\Commands;

use App\Models\Cidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncCidadesFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cidades:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar cidades da API externa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronização de cidades...');

        $page = 1;
        $totalCriadas = 0;
        $totalAtualizadas = 0;

        do {
            $this->info("Processando página {$page}...");

            try {
                $response = Http::timeout(30)->get('http://lotus-api.cloud.zielo.com.br/api/get_cidades_prestadores', [
                    'page' => $page
                ]);

                if (!$response->successful()) {
                    $this->error("Erro ao acessar a API: HTTP {$response->status()}");
                    return 1;
                }

                $data = $response->json();
                $cidades = $data['itens'] ?? [];

                if (empty($cidades)) {
                    $this->warn("Nenhuma cidade encontrada na página {$page}");
                    break;
                }

                foreach ($cidades as $cidadeData) {
                    $nome = $cidadeData['nome'] ?? '';
                    $uf = $cidadeData['uf'] ?? '';
                    $nomeCompleto = $cidadeData['nomes'] ?? '';

                    if (empty($nome) || empty($uf)) {
                        $this->warn("Cidade com dados incompletos: {$nome} - {$uf}");
                        continue;
                    }

                    // Extrair UF do nome completo (ex: "SERRANA - SP" -> "SP")
                    $ufCode = '';
                    if (preg_match('/\s*-\s*([A-Z]{2})$/', $nomeCompleto, $matches)) {
                        $ufCode = $matches[1];
                    } else {
                        // Fallback: usar os primeiros 2 caracteres da UF
                        $ufCode = substr($uf, 0, 2);
                    }

                    // Gerar slug baseado no nome
                    $slug = Str::slug($nome);

                    // Criar ou atualizar cidade
                    $cidade = Cidade::updateOrCreate(
                        [
                            'nome' => $nome,
                            'uf' => $ufCode
                        ],
                        [
                            'slug' => $slug,
                            'nome_completo' => $nomeCompleto
                        ]
                    );

                    if ($cidade->wasRecentlyCreated) {
                        $totalCriadas++;
                        $this->line("✓ Criada: {$nome} - {$ufCode}");
                    } else {
                        $totalAtualizadas++;
                        $this->line("✓ Atualizada: {$nome} - {$ufCode}");
                    }
                }

                // Verificar se há próxima página
                $links = $data['links'] ?? [];
                $nextLink = $links['next'] ?? null;
                
                if (!$nextLink) {
                    break;
                }

                $page++;

            } catch (\Exception $e) {
                $this->error("Erro ao processar página {$page}: " . $e->getMessage());
                return 1;
            }

        } while (true);

        $this->info('');
        $this->info('Sincronização concluída!');
        $this->info("Total criadas: {$totalCriadas}");
        $this->info("Total atualizadas: {$totalAtualizadas}");

        return 0;
    }
}
