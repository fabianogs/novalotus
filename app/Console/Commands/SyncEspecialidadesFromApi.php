<?php

namespace App\Console\Commands;

use App\Models\Especialidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncEspecialidadesFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'especialidades:sync {--force : Força a sincronização mesmo se já existir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza especialidades da API externa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronização de especialidades...');

        $apiUrl = 'http://lotus-api.cloud.zielo.com.br/api/get_especialidades';
        $page = 1;
        $totalSynced = 0;
        $totalUpdated = 0;
        $totalErrors = 0;

        try {
            do {
                $this->info("Processando página {$page}...");

                $response = Http::timeout(30)->get($apiUrl, ['page' => $page]);
                
                if (!$response->successful()) {
                    $this->error("Erro ao acessar a API: " . $response->status());
                    return 1;
                }

                $data = $response->json();
                
                if (!isset($data['itens']) || !is_array($data['itens'])) {
                    $this->error('Formato de resposta inválido da API');
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
                            $this->line("✓ Criada: {$item['descricao']}");
                        } else {
                            $totalUpdated++;
                            $this->line("✓ Atualizada: {$item['descricao']}");
                        }

                    } catch (\Exception $e) {
                        $totalErrors++;
                        $this->error("✗ Erro ao processar {$item['descricao']}: " . $e->getMessage());
                    }
                }

                // Verificar se há próxima página
                $hasNextPage = isset($data['links']['next']) && $data['links']['next'] !== null;
                $page++;

            } while ($hasNextPage);

            $this->newLine();
            $this->info("Sincronização concluída!");
            $this->info("Total criadas: {$totalSynced}");
            $this->info("Total atualizadas: {$totalUpdated}");
            
            if ($totalErrors > 0) {
                $this->warn("Total de erros: {$totalErrors}");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Erro durante a sincronização: " . $e->getMessage());
            return 1;
        }
    }
}
