<?php

namespace App\Console\Commands;

use App\Models\Especialidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ScheduleEspecialidadesSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'especialidades:schedule-sync {--silent : Executa sem output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza especialidades da API externa (para uso com cron)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $silent = $this->option('silent');
        
        if (!$silent) {
            $this->info('Iniciando sincronização automática de especialidades...');
        }

        $apiUrl = 'http://lotus-api.cloud.zielo.com.br/api/get_especialidades';
        $page = 1;
        $totalSynced = 0;
        $totalUpdated = 0;
        $totalErrors = 0;
        $startTime = now();

        try {
            do {
                if (!$silent) {
                    $this->info("Processando página {$page}...");
                }

                $response = Http::timeout(30)->get($apiUrl, ['page' => $page]);
                
                if (!$response->successful()) {
                    $errorMsg = "Erro ao acessar a API: " . $response->status();
                    Log::error('Sincronização de especialidades falhou: ' . $errorMsg);
                    
                    if (!$silent) {
                        $this->error($errorMsg);
                    }
                    return 1;
                }

                $data = $response->json();
                
                if (!isset($data['itens']) || !is_array($data['itens'])) {
                    $errorMsg = 'Formato de resposta inválido da API';
                    Log::error('Sincronização de especialidades falhou: ' . $errorMsg);
                    
                    if (!$silent) {
                        $this->error($errorMsg);
                    }
                    return 1;
                }

                foreach ($data['itens'] as $item) {
                    try {
                        $especialidade = Especialidade::updateOrCreate(
                            ['id' => $item['id']],
                            [
                                'descricao' => $item['descricao'],
                                'slug' => Str::slug($item['descricao']),
                            ]
                        );

                        if ($especialidade->wasRecentlyCreated) {
                            $totalSynced++;
                            if (!$silent) {
                                $this->line("✓ Criada: {$item['descricao']}");
                            }
                        } else {
                            $totalUpdated++;
                            if (!$silent) {
                                $this->line("✓ Atualizada: {$item['descricao']}");
                            }
                        }

                    } catch (\Exception $e) {
                        $totalErrors++;
                        $errorMsg = "Erro ao processar {$item['descricao']}: " . $e->getMessage();
                        Log::error($errorMsg);
                        
                        if (!$silent) {
                            $this->error("✗ {$errorMsg}");
                        }
                    }
                }

                // Verificar se há próxima página
                $hasNextPage = isset($data['links']['next']) && $data['links']['next'] !== null;
                $page++;

            } while ($hasNextPage);

            $duration = now()->diffInSeconds($startTime);
            
            // Log do resultado
            $logMessage = "Sincronização concluída: {$totalSynced} criadas, {$totalUpdated} atualizadas, {$totalErrors} erros em {$duration}s";
            Log::info($logMessage);

            if (!$silent) {
                $this->newLine();
                $this->info("Sincronização concluída em {$duration} segundos!");
                $this->info("Total criadas: {$totalSynced}");
                $this->info("Total atualizadas: {$totalUpdated}");
                
                if ($totalErrors > 0) {
                    $this->warn("Total de erros: {$totalErrors}");
                }
            }

            return 0;

        } catch (\Exception $e) {
            $errorMsg = "Erro durante a sincronização: " . $e->getMessage();
            Log::error($errorMsg);
            
            if (!$silent) {
                $this->error($errorMsg);
            }
            return 1;
        }
    }
}
