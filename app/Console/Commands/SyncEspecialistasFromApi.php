<?php

namespace App\Console\Commands;

use App\Models\Especialista;
use App\Models\Especialidade;
use App\Models\Endereco;
use App\Models\Telefone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Traits\SyncLoggable;

class SyncEspecialistasFromApi extends Command
{
    use SyncLoggable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'especialistas:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza especialistas da API externa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->startSyncLog('especialistas');
        
        try {
            $this->info('Iniciando sincronização de especialistas...');
            
            $page = 1;
            $totalProcessed = 0;
            
            do {
                $this->info("Processando página {$page}...");
                
                $response = Http::get('http://lotus-api.cloud.zielo.com.br/api/get_credenciados', [
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
                        $especialista = $this->processarEspecialista($item);
                        
                        if ($especialista) {
                            $this->processarEnderecos($especialista, $item['enderecos'] ?? []);
                            $this->processarTelefones($especialista, $item['telefones'] ?? []);
                            $this->processarEspecialidades($especialista, $item['especialidades'] ?? []);
                            
                            $totalProcessed++;
                        }
                        
                    } catch (\Exception $e) {
                        $nome = $item['nome'] ?? 'Desconhecido';
                        $this->error("✗ Erro ao processar especialista {$nome}: " . $e->getMessage());
                        $this->incrementErrorItems();
                    }
                }
                
                $page++;
                
            } while (isset($data['links']['next']));
            
            $this->addSyncDetail('total_pages', $page - 1);
            $this->addSyncDetail('api_url', 'http://lotus-api.cloud.zielo.com.br/api/get_credenciados');
            
            $this->info("Sincronização concluída!");
            $this->info("Total processado: {$totalProcessed}");
            
            $this->finishSyncLogSuccess("Sincronização concluída com {$totalProcessed} especialistas processados");
            
            return 0;
            
        } catch (\Exception $e) {
            $errorMessage = "Erro durante a sincronização: " . $e->getMessage();
            $this->error($errorMessage);
            $this->finishSyncLogError($errorMessage, $e);
            return 1;
        }
    }

    /**
     * Processar dados do especialista
     */
    private function processarEspecialista($data)
    {
        $especialista = Especialista::updateOrCreate(
            ['nome' => $data['nome']],
            [
                'nome_fantasia' => $data['nome_fantasia'] ?? null,
                'conselho' => $data['conselho'] ?? null,
                'registro' => $data['registro'] ?? null,
                'registro_uf' => $data['registro_uf'] ?? null,
                'cidade_id' => null,
                'endereco' => $data['endereco'] ?? null,
                'necessidade_id' => null,
                'slug' => Str::slug($data['nome']),
            ]
        );
        
        if ($especialista->wasRecentlyCreated) {
            $this->line("✓ Criado: {$data['nome']}");
            $this->incrementCreatedItems();
        } else {
            $this->line("✓ Atualizado: {$data['nome']}");
            $this->incrementUpdatedItems();
        }
        
        return $especialista;
    }

    /**
     * Processar endereços do especialista
     */
    private function processarEnderecos($especialista, $enderecos)
    {
        // Remover endereços existentes
        $especialista->enderecos()->delete();
        
        foreach ($enderecos as $enderecoData) {
            Endereco::create([
                'especialista_id' => $especialista->id,
                'uf' => $enderecoData['uf'] ?? null,
                'cidade_nome' => $enderecoData['cidade_nome'] ?? null,
                'cep' => $enderecoData['cep'] ?? null,
                'bairro' => $enderecoData['bairro'] ?? null,
                'logradouro' => $enderecoData['logradouro'] ?? null,
                'numero' => $enderecoData['numero'] ?? null,
                'complemento' => $enderecoData['complemento'] ?? null,
            ]);
        }
    }

    /**
     * Processar telefones do especialista
     */
    private function processarTelefones($especialista, $telefones)
    {
        // Remover telefones existentes
        $especialista->telefones()->delete();
        
        foreach ($telefones as $telefoneData) {
            Telefone::create([
                'especialista_id' => $especialista->id,
                'numero' => $telefoneData['numero'] ?? null,
                'observacao' => $telefoneData['observacao'] ?? null,
            ]);
        }
    }

    /**
     * Processar especialidades do especialista
     */
    private function processarEspecialidades($especialista, $especialidades)
    {
        $especialidadeIds = [];
        
        foreach ($especialidades as $especialidadeData) {
            $especialidade = Especialidade::find($especialidadeData['especialidade_id']);
            if ($especialidade) {
                $especialidadeIds[] = $especialidade->id;
            }
        }
        
        $especialista->especialidades()->sync($especialidadeIds);
    }
}
