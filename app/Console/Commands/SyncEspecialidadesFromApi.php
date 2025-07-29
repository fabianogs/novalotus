<?php

namespace App\Console\Commands;

use App\Models\Especialidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Traits\SyncLoggable;

class SyncEspecialidadesFromApi extends Command
{
    use SyncLoggable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'especialidades:sync';

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
        $this->startSyncLog('especialidades');
        
        try {
            $this->info('Iniciando sincronização de especialidades...');
            
            $page = 1;
            $totalProcessed = 0;
            
            do {
                $this->info("Processando página {$page}...");
                
                $response = Http::get('http://lotus-api.cloud.zielo.com.br/api/get_especialidades', [
                    'page' => $page
                ]);
                
                if (!$response->successful()) {
                    $errorMessage = "Erro ao acessar API: " . $response->status();
                    $this->error($errorMessage);
                    $this->finishSyncLogError($errorMessage);
                    return 1;
                }
                
                $data = $response->json();
                $items = $data['itens'] ?? [];
                
                if (empty($items)) {
                    break;
                }
                
                foreach ($items as $item) {
                    try {
                        $especialidade = Especialidade::updateOrCreate(
                            ['id' => $item['id']],
                            [
                                'descricao' => $item['descricao'],
                                'slug' => Str::slug($item['descricao']),
                            ]
                        );
                        
                        if ($especialidade->wasRecentlyCreated) {
                            $this->line("✓ Criado: {$item['descricao']}");
                            $this->incrementCreatedItems();
                        } else {
                            $this->line("✓ Atualizado: {$item['descricao']}");
                            $this->incrementUpdatedItems();
                        }
                        
                        $totalProcessed++;
                        
                    } catch (\Exception $e) {
                        $this->error("✗ Erro ao processar especialidade {$item['descricao']}: " . $e->getMessage());
                        $this->incrementErrorItems();
                    }
                }
                
                $page++;
                
            } while (isset($data['links']['next']));
            
            $this->addSyncDetail('total_pages', $page - 1);
            $this->addSyncDetail('api_url', 'http://lotus-api.cloud.zielo.com.br/api/get_especialidades');
            
            $this->info("Sincronização concluída!");
            $this->info("Total processado: {$totalProcessed}");
            
            $this->finishSyncLogSuccess("Sincronização concluída com {$totalProcessed} especialidades processadas");
            
            return 0;
            
        } catch (\Exception $e) {
            $errorMessage = "Erro durante a sincronização: " . $e->getMessage();
            $this->error($errorMessage);
            $this->finishSyncLogError($errorMessage, $e);
            return 1;
        }
    }
}
