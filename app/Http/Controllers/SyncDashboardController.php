<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use App\Models\Cidade;
use App\Models\Especialista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncDashboardController extends Controller
{
    /**
     * Display the main sync dashboard.
     */
    public function index()
    {
        // Estatísticas locais
        $stats = [
            'especialidades' => [
                'total' => Especialidade::count(),
                'ultima_atualizacao' => Especialidade::max('updated_at'),
                'atualizadas_hoje' => Especialidade::whereDate('updated_at', today())->count(),
                'atualizadas_semana' => Especialidade::whereDate('updated_at', '>=', now()->subDays(7))->count(),
            ],
            'cidades' => [
                'total' => Cidade::count(),
                'ultima_atualizacao' => Cidade::max('updated_at'),
                'atualizadas_hoje' => Cidade::whereDate('updated_at', today())->count(),
                'atualizadas_semana' => Cidade::whereDate('updated_at', '>=', now()->subDays(7))->count(),
            ],
            'especialistas' => [
                'total' => Especialista::count(),
                'ultima_atualizacao' => Especialista::max('updated_at'),
                'atualizados_hoje' => Especialista::whereDate('updated_at', today())->count(),
                'atualizados_semana' => Especialista::whereDate('updated_at', '>=', now()->subDays(7))->count(),
            ]
        ];

        // Status das APIs
        $apiStatus = [
            'especialidades' => $this->checkEspecialidadesApiStatus(),
            'cidades' => $this->checkCidadesApiStatus(),
            'especialistas' => $this->checkEspecialistasApiStatus(),
        ];

        // Logs recentes
        $recentLogs = $this->getRecentSyncLogs();

        return view('sync-dashboard.index', compact('stats', 'apiStatus', 'recentLogs'));
    }

    /**
     * Execute manual sync for a specific entity.
     */
    public function syncAjax(Request $request)
    {
        $entity = $request->input('entity');
        
        try {
            switch ($entity) {
                case 'especialidades':
                    \Artisan::call('especialidades:sync');
                    $message = 'Sincronização de especialidades executada com sucesso!';
                    break;
                    
                case 'cidades':
                    \Artisan::call('cidades:sync');
                    $message = 'Sincronização de cidades executada com sucesso!';
                    break;
                    
                case 'especialistas':
                    \Artisan::call('especialistas:sync');
                    $message = 'Sincronização de especialistas executada com sucesso!';
                    break;
                    
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Entidade inválida'
                    ], 400);
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro na sincronização de {$entity}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro durante a sincronização: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get status for all entities.
     */
    public function statusAjax()
    {
        $stats = [
            'especialidades' => [
                'total' => Especialidade::count(),
                'ultima_atualizacao' => Especialidade::max('updated_at'),
                'atualizadas_hoje' => Especialidade::whereDate('updated_at', today())->count(),
                'atualizadas_semana' => Especialidade::whereDate('updated_at', '>=', now()->subDays(7))->count(),
            ],
            'cidades' => [
                'total' => Cidade::count(),
                'ultima_atualizacao' => Cidade::max('updated_at'),
                'atualizadas_hoje' => Cidade::whereDate('updated_at', today())->count(),
                'atualizadas_semana' => Cidade::whereDate('updated_at', '>=', now()->subDays(7))->count(),
            ],
            'especialistas' => [
                'total' => Especialista::count(),
                'ultima_atualizacao' => Especialista::max('updated_at'),
                'atualizados_hoje' => Especialista::whereDate('updated_at', today())->count(),
                'atualizados_semana' => Especialista::whereDate('updated_at', '>=', now()->subDays(7))->count(),
            ]
        ];

        $apiStatus = [
            'especialidades' => $this->checkEspecialidadesApiStatus(),
            'cidades' => $this->checkCidadesApiStatus(),
            'especialistas' => $this->checkEspecialistasApiStatus(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'apiStatus' => $apiStatus
        ]);
    }

    /**
     * Check Especialidades API status.
     */
    private function checkEspecialidadesApiStatus()
    {
        try {
            $response = Http::timeout(10)->get('http://lotus-api.cloud.zielo.com.br/api/get_especialidades');
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => 'online',
                    'total' => $data['total_items'] ?? 0,
                    'pages' => $data['page_count'] ?? 0,
                    'message' => 'API funcionando normalmente'
                ];
            }
            
            return [
                'status' => 'error',
                'total' => 0,
                'pages' => 0,
                'message' => 'Erro na API: ' . $response->status()
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'total' => 0,
                'pages' => 0,
                'message' => 'API indisponível: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check Cidades API status.
     */
    private function checkCidadesApiStatus()
    {
        try {
            $response = Http::timeout(10)->get('http://lotus-api.cloud.zielo.com.br/api/get_cidades_prestadores');
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => 'online',
                    'total' => $data['total_items'] ?? 0,
                    'pages' => $data['page_count'] ?? 0,
                    'message' => 'API funcionando normalmente'
                ];
            }
            
            return [
                'status' => 'error',
                'total' => 0,
                'pages' => 0,
                'message' => 'Erro na API: ' . $response->status()
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'total' => 0,
                'pages' => 0,
                'message' => 'API indisponível: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check Especialistas API status.
     */
    private function checkEspecialistasApiStatus()
    {
        try {
            $response = Http::timeout(10)->get('http://lotus-api.cloud.zielo.com.br/api/get_credenciados');
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => 'online',
                    'total' => $data['total_items'] ?? 0,
                    'pages' => $data['page_count'] ?? 0,
                    'message' => 'API funcionando normalmente'
                ];
            }
            
            return [
                'status' => 'error',
                'total' => 0,
                'pages' => 0,
                'message' => 'Erro na API: ' . $response->status()
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'total' => 0,
                'pages' => 0,
                'message' => 'API indisponível: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get recent sync logs.
     */
    private function getRecentSyncLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return [];
        }

        $logs = [];
        $lines = file($logFile);
        $recentLines = array_slice($lines, -100); // Últimas 100 linhas

        foreach ($recentLines as $line) {
            if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] .*?: (.*)/', $line, $matches)) {
                $timestamp = $matches[1];
                $message = trim($matches[2]);
                
                // Filtrar apenas logs relacionados a sincronização
                if (strpos($message, 'sincronização') !== false || 
                    strpos($message, 'sync') !== false ||
                    strpos($message, 'especialidades') !== false ||
                    strpos($message, 'cidades') !== false ||
                    strpos($message, 'especialistas') !== false) {
                    
                    $logs[] = [
                        'timestamp' => $timestamp,
                        'message' => $message,
                        'time_ago' => \Carbon\Carbon::parse($timestamp)->diffForHumans()
                    ];
                }
            }
        }

        // Retornar apenas os últimos 10 logs
        return array_slice(array_reverse($logs), 0, 10);
    }
}