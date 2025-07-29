<?php

namespace App\Console\Commands;

use App\Models\Especialista;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckEspecialistasStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'especialistas:status {--json : Retorna resultado em JSON}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar status da sincronização de especialistas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Estatísticas locais
        $totalLocal = Especialista::count();
        $ultimaAtualizacao = Especialista::max('updated_at');
        $especialistasHoje = Especialista::whereDate('updated_at', today())->count();
        $especialistasSemana = Especialista::whereDate('updated_at', '>=', now()->subDays(7))->count();

        // Verificar API externa
        $apiStatus = $this->checkApiStatus();

        // Determinar status geral
        $statusGeral = $this->getOverallStatus($totalLocal, $apiStatus);

        $stats = [
            'status' => $statusGeral,
            'local' => [
                'total' => $totalLocal,
                'atualizados_hoje' => $especialistasHoje,
                'atualizados_semana' => $especialistasSemana,
                'ultima_atualizacao' => $ultimaAtualizacao instanceof \Carbon\Carbon ? $ultimaAtualizacao->format('d/m/Y H:i:s') : 'Nunca'
            ],
            'api' => $apiStatus
        ];

        if ($this->option('json')) {
            $this->output->write(json_encode($stats, JSON_PRETTY_PRINT));
            return 0;
        }

        // Exibir resultados
        $this->info('=== Status da Sincronização de Especialistas ===');
        $this->info('');
        
        $this->info('📊 Estatísticas Locais:');
        $this->line("   Total de especialistas: {$totalLocal}");
        $this->line("   Atualizados hoje: {$especialistasHoje}");
        $this->line("   Atualizados na semana: {$especialistasSemana}");
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
            $response = Http::timeout(10)->get('http://lotus-api.cloud.zielo.com.br/api/get_credenciados');
            
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

        if ($totalLocal < 10) {
            return 'warning';
        }

        return 'ok';
    }
}
