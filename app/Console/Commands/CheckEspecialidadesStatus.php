<?php

namespace App\Console\Commands;

use App\Models\Especialidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckEspecialidadesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'especialidades:status {--json : Retorna resultado em JSON}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica o status da sincronização de especialidades';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando status da sincronização de especialidades...');

        // Estatísticas locais
        $totalLocal = Especialidade::count();
        $ultimaAtualizacao = Especialidade::max('updated_at');
        $especialidadesHoje = Especialidade::whereDate('updated_at', today())->count();

        // Verificar API externa
        $apiStatus = $this->checkApiStatus();

        $stats = [
            'local' => [
                'total' => $totalLocal,
                'ultima_atualizacao' => $ultimaAtualizacao instanceof \Carbon\Carbon ? $ultimaAtualizacao->format('d/m/Y H:i:s') : 'Nunca',
                'atualizadas_hoje' => $especialidadesHoje,
            ],
            'api' => $apiStatus,
            'status' => $this->getOverallStatus($totalLocal, $apiStatus)
        ];

        if ($this->option('json')) {
            $this->line(json_encode($stats, JSON_PRETTY_PRINT));
            return 0;
        }

        // Exibir resultados
        $this->newLine();
        $this->info('📊 ESTATÍSTICAS LOCAIS');
        $this->line("Total de especialidades: {$stats['local']['total']}");
        $this->line("Última atualização: {$stats['local']['ultima_atualizacao']}");
        $this->line("Atualizadas hoje: {$stats['local']['atualizadas_hoje']}");

        $this->newLine();
        $this->info('🌐 STATUS DA API');
        if ($apiStatus['online']) {
            $this->line("✅ API online");
            $this->line("Total na API: {$apiStatus['total']}");
            $this->line("Páginas disponíveis: {$apiStatus['pages']}");
        } else {
            $this->error("❌ API offline");
            $this->line("Erro: {$apiStatus['error']}");
        }

        $this->newLine();
        $this->info('📈 STATUS GERAL');
        $status = $stats['status'];
        if ($status === 'ok') {
            $this->info("✅ Sincronização OK");
        } elseif ($status === 'warning') {
            $this->warn("⚠️  Sincronização com avisos");
        } else {
            $this->error("❌ Sincronização com problemas");
        }

        return 0;
    }

    private function checkApiStatus()
    {
        try {
            $response = Http::timeout(10)->get('http://lotus-api.cloud.zielo.com.br/api/get_especialidades');
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'online' => true,
                    'total' => $data['total_items'] ?? 'N/A',
                    'pages' => $data['page_count'] ?? 'N/A',
                    'error' => null
                ];
            } else {
                return [
                    'online' => false,
                    'total' => 0,
                    'pages' => 0,
                    'error' => "HTTP {$response->status()}"
                ];
            }
        } catch (\Exception $e) {
            return [
                'online' => false,
                'total' => 0,
                'pages' => 0,
                'error' => $e->getMessage()
            ];
        }
    }

    private function getOverallStatus($totalLocal, $apiStatus)
    {
        if (!$apiStatus['online']) {
            return 'error';
        }

        if ($totalLocal == 0) {
            return 'error';
        }

        if ($totalLocal < 50) { // Assumindo que deve ter pelo menos 50 especialidades
            return 'warning';
        }

        return 'ok';
    }
}
