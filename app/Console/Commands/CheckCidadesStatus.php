<?php

namespace App\Console\Commands;

use App\Models\Cidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckCidadesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cidades:status {--json : Retorna resultado em JSON}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar status da sincronização de cidades';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Estatísticas locais
        $totalLocal = Cidade::count();
        $ultimaAtualizacao = Cidade::max('updated_at');
        $cidadesHoje = Cidade::whereDate('updated_at', today())->count();
        $cidadesSemana = Cidade::whereDate('updated_at', '>=', now()->subDays(7))->count();

        // Verificar API externa
        $apiStatus = $this->checkApiStatus();

        // Determinar status geral
        $statusGeral = $this->getOverallStatus($totalLocal, $apiStatus);

        $stats = [
            'status' => $statusGeral,
            'local' => [
                'total' => $totalLocal,
                'atualizadas_hoje' => $cidadesHoje,
                'atualizadas_semana' => $cidadesSemana,
                'ultima_atualizacao' => $ultimaAtualizacao instanceof \Carbon\Carbon ? $ultimaAtualizacao->format('d/m/Y H:i:s') : 'Nunca'
            ],
            'api' => $apiStatus
        ];

        if ($this->option('json')) {
            $this->output->write(json_encode($stats, JSON_PRETTY_PRINT));
            return 0;
        }

        // Exibir resultados
        $this->info('=== Status da Sincronização de Cidades ===');
        $this->info('');
        
        $this->info('📊 Estatísticas Locais:');
        $this->line("   Total de cidades: {$totalLocal}");
        $this->line("   Atualizadas hoje: {$cidadesHoje}");
        $this->line("   Atualizadas na semana: {$cidadesSemana}");
        $this->line("   Última atualização: {$stats['local']['ultima_atualizacao']}");
        
        $this->info('');
        $this->info('🌐 Status da API:');
        $this->line("   Status: " . ($apiStatus['online'] ? '🟢 Online' : '🔴 Offline'));
        
        if ($apiStatus['online']) {
            $this->line("   Total na API: {$apiStatus['total']}");
            $this->line("   Páginas: {$apiStatus['pages']}");
        } else {
            $this->line("   Erro: {$apiStatus['error']}");
        }
        
        $this->info('');
        $this->info('📈 Status Geral: ' . strtoupper($statusGeral));
        
        switch ($statusGeral) {
            case 'ok':
                $this->info('✅ Sistema funcionando normalmente');
                break;
            case 'warning':
                $this->warn('⚠️ Sistema com avisos - verifique os detalhes');
                break;
            case 'error':
                $this->error('❌ Sistema com problemas - ação necessária');
                break;
        }

        return 0;
    }

    /**
     * Verificar status da API
     */
    private function checkApiStatus()
    {
        try {
            $response = Http::timeout(10)->get('http://lotus-api.cloud.zielo.com.br/api/get_cidades_prestadores');
            
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

    /**
     * Obter status geral
     */
    private function getOverallStatus($totalLocal, $apiStatus)
    {
        if (!$apiStatus['online']) {
            return 'error';
        }

        if ($totalLocal == 0) {
            return 'error';
        }

        if ($totalLocal < 5) {
            return 'warning';
        }

        return 'ok';
    }
}
