<?php

namespace App\Console\Commands;

use App\Models\Especialista;
use App\Models\Endereco;
use App\Models\Telefone;
use App\Models\Especialidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncEspecialistasFromApi extends Command
{
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
    protected $description = 'Sincronizar especialistas da API externa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronização de especialistas...');

        $page = 1;
        $totalCriados = 0;
        $totalAtualizados = 0;

        do {
            $this->info("Processando página {$page}...");

            try {
                $response = Http::timeout(30)->get('http://lotus-api.cloud.zielo.com.br/api/get_credenciados', [
                    'page' => $page
                ]);

                if (!$response->successful()) {
                    $this->error("Erro ao acessar a API: HTTP {$response->status()}");
                    return 1;
                }

                $data = $response->json();
                $especialistas = $data['itens'] ?? [];

                if (empty($especialistas)) {
                    $this->warn("Nenhum especialista encontrado na página {$page}");
                    break;
                }

                foreach ($especialistas as $especialistaData) {
                    $this->processarEspecialista($especialistaData, $totalCriados, $totalAtualizados);
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
        $this->info("Total criados: {$totalCriados}");
        $this->info("Total atualizados: {$totalAtualizados}");

        return 0;
    }

    /**
     * Processar um especialista individual
     */
    private function processarEspecialista($data, &$totalCriados, &$totalAtualizados)
    {
        $nome = $data['nome'] ?? '';
        $nomeFantasia = $data['nome_fantasia'] ?? '';
        $conselho = $data['conselho'] ?? '';
        $registro = $data['registro'] ?? '';
        $registroUf = $data['registro_uf'] ?? '';

        if (empty($nome)) {
            $this->warn("Especialista com nome vazio: ID {$data['id']}");
            return;
        }

        // Gerar slug baseado no nome
        $slug = Str::slug($nome);

        // Criar ou atualizar especialista
        $especialista = Especialista::updateOrCreate(
            [
                'nome' => $nome,
                'conselho' => $conselho,
                'registro' => $registro
            ],
            [
                'nome_fantasia' => $nomeFantasia,
                'registro_uf' => $registroUf,
                'slug' => $slug,
                'cidade_id' => null,
                'necessidade_id' => null,
                'endereco' => null,
                'foto' => null
            ]
        );

        // Processar endereços
        $this->processarEnderecos($especialista, $data['enderecos'] ?? []);

        // Processar telefones
        $this->processarTelefones($especialista, $data['telefones'] ?? []);

        // Processar especialidades
        $this->processarEspecialidades($especialista, $data['especialidades'] ?? []);

        if ($especialista->wasRecentlyCreated) {
            $totalCriados++;
            $this->line("✓ Criado: {$nome}");
        } else {
            $totalAtualizados++;
            $this->line("✓ Atualizado: {$nome}");
        }
    }

    /**
     * Processar endereços do especialista
     */
    private function processarEnderecos($especialista, $enderecosData)
    {
        // Limpar endereços existentes
        $especialista->enderecos()->delete();

        foreach ($enderecosData as $enderecoData) {
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
    private function processarTelefones($especialista, $telefonesData)
    {
        // Limpar telefones existentes
        $especialista->telefones()->delete();

        foreach ($telefonesData as $telefoneData) {
            Telefone::create([
                'especialista_id' => $especialista->id,
                'numero' => $telefoneData['numero'] ?? '',
                'observacao' => $telefoneData['observacao'] ?? null,
            ]);
        }
    }

    /**
     * Processar especialidades do especialista
     */
    private function processarEspecialidades($especialista, $especialidadesData)
    {
        $especialidadeIds = [];

        foreach ($especialidadesData as $especialidadeData) {
            $especialidadeId = $especialidadeData['especialidade_id'] ?? null;
            
            if ($especialidadeId) {
                // Buscar especialidade pelo ID da API
                $especialidade = Especialidade::where('id', $especialidadeId)->first();
                
                if ($especialidade) {
                    $especialidadeIds[] = $especialidade->id;
                } else {
                    $this->warn("Especialidade não encontrada: ID {$especialidadeId} - {$especialidadeData['descricao']}");
                }
            }
        }

        // Sincronizar especialidades
        $especialista->especialidades()->sync($especialidadeIds);
    }
}
