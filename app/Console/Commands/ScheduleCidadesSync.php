<?php

namespace App\Console\Commands;

use App\Models\Cidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ScheduleCidadesSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cidades:schedule-sync {--silent : Executa sem output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronização agendada de cidades da API externa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $silent = $this->option('silent');
        
        if (!$silent) {
            $this->info('Iniciando sincronização agendada de cidades...');
        }

        $page = 1;
        $totalCriadas = 0;
        $totalAtualizadas = 0;

        try {
            do {
                if (!$silent) {
                    $this->info("Processando página {$page}...");
                }

                $response = Http::timeout(30)->get('http://lotus-api.cloud.zielo.com.br/api/get_cidades_prestadores', [
                    'page' => $page
                ]);

                if (!$response->successful()) {
                    $errorMsg = "Erro ao acessar a API de cidades: HTTP {$response->status()}";
                    Log::error($errorMsg);
                    
                    if (!$silent) {
                        $this->error($errorMsg);
                    }
                    return 1;
                }

                $data = $response->json();
                $cidades = $data['itens'] ?? [];

                if (empty($cidades)) {
                    if (!$silent) {
                        $this->warn("Nenhuma cidade encontrada na página {$page}");
                    }
                    break;
                }

                foreach ($cidades as $cidadeData) {
                    $nome = $cidadeData['nome'] ?? '';
                    $uf = $cidadeData['uf'] ?? '';
                    $nomeCompleto = $cidadeData['nomes'] ?? '';

                    if (empty($nome) || empty($uf)) {
                        $warningMsg = "Cidade com dados incompletos: {$nome} - {$uf}";
                        Log::warning($warningMsg);
                        
                        if (!$silent) {
                            $this->warn($warningMsg);
                        }
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
                        $logMsg = "Cidade criada: {$nome} - {$ufCode}";
                        Log::info($logMsg);
                        
                        if (!$silent) {
                            $this->line("✓ {$logMsg}");
                        }
                    } else {
                        $totalAtualizadas++;
                        $logMsg = "Cidade atualizada: {$nome} - {$ufCode}";
                        Log::info($logMsg);
                        
                        if (!$silent) {
                            $this->line("✓ {$logMsg}");
                        }
                    }
                }

                // Verificar se há próxima página
                $links = $data['links'] ?? [];
                $nextLink = $links['next'] ?? null;
                
                if (!$nextLink) {
                    break;
                }

                $page++;

            } while (true);

            $summaryMsg = "Sincronização de cidades concluída - Criadas: {$totalCriadas}, Atualizadas: {$totalAtualizadas}";
            Log::info($summaryMsg);
            
            if (!$silent) {
                $this->info('');
                $this->info('Sincronização concluída!');
                $this->info("Total criadas: {$totalCriadas}");
                $this->info("Total atualizadas: {$totalAtualizadas}");
            }

            return 0;

        } catch (\Exception $e) {
            $errorMsg = "Erro na sincronização de cidades: " . $e->getMessage();
            Log::error($errorMsg);
            
            if (!$silent) {
                $this->error($errorMsg);
            }
            return 1;
        }
    }
}
