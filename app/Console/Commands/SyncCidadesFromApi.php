<?php

namespace App\Console\Commands;

use App\Models\Cidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Traits\SyncLoggable;

class SyncCidadesFromApi extends Command
{
    use SyncLoggable;

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
    protected $description = 'Sincroniza cidades da API externa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->startSyncLog('cidades');
        
        try {
            $this->info('Iniciando sincronização de cidades...');
            
            $page = 1;
            $totalProcessed = 0;
            
            do {
                $this->info("Processando página {$page}...");
                
                $response = Http::get('http://lotus-api.cloud.zielo.com.br/api/get_cidades_prestadores', [
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
                        // Extrair UF do campo nomes (ex: "SERRANA - SP" -> "SP")
                        $uf = null;
                        if (isset($item['nomes']) && preg_match('/\b([A-Z]{2})\s*$/', $item['nomes'], $matches)) {
                            $uf = $matches[1];
                        } elseif (isset($item['uf'])) {
                            $uf = substr($item['uf'], 0, 2);
                        }
                        
                        $cidade = Cidade::updateOrCreate(
                            [
                                'nome' => $item['nome'],
                                'uf' => $uf,
                            ],
                            [
                                'nome_completo' => $item['nomes'] ?? null,
                                'slug' => Str::slug($item['nome']),
                            ]
                        );
                        
                        if ($cidade->wasRecentlyCreated) {
                            $this->line("✓ Criado: {$item['nome']} - {$uf}");
                            $this->incrementCreatedItems();
                        } else {
                            $this->line("✓ Atualizado: {$item['nome']} - {$uf}");
                            $this->incrementUpdatedItems();
                        }
                        
                        $totalProcessed++;
                        
                    } catch (\Exception $e) {
                        $this->error("✗ Erro ao processar cidade {$item['nome']}: " . $e->getMessage());
                        $this->incrementErrorItems();
                    }
                }
                
                $page++;
                
            } while (isset($data['links']['next']));
            
            $this->addSyncDetail('total_pages', $page - 1);
            $this->addSyncDetail('api_url', 'http://lotus-api.cloud.zielo.com.br/api/get_cidades_prestadores');
            
            $this->info("Sincronização concluída!");
            $this->info("Total processado: {$totalProcessed}");
            
            $this->finishSyncLogSuccess("Sincronização concluída com {$totalProcessed} cidades processadas");
            
            return 0;
            
        } catch (\Exception $e) {
            $errorMessage = "Erro durante a sincronização: " . $e->getMessage();
            $this->error($errorMessage);
            $this->finishSyncLogError($errorMessage, $e);
            return 1;
        }
    }
}
