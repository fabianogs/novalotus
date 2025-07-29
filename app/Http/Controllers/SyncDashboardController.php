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
     * Obter logs recentes de sincronização
     */
    private function getRecentSyncLogs()
    {
        try {
            $logs = \App\Models\SyncLog::orderBy('started_at', 'desc')
                ->limit(10)
                ->get();

            $formattedLogs = [];
            
            foreach ($logs as $log) {
                $statusIcon = match($log->status) {
                    'success' => '✅',
                    'error' => '❌',
                    'partial' => '⚠️',
                    'running' => '🔄',
                    default => '❓'
                };

                $message = "{$statusIcon} Sincronização de " . ucfirst($log->entity);
                
                if ($log->status === 'success') {
                    $message .= " concluída";
                    if ($log->created_items > 0 || $log->updated_items > 0) {
                        $message .= " ({$log->summary})";
                    }
                } elseif ($log->status === 'error') {
                    $message .= " falhou: " . ($log->error_message ?: 'Erro desconhecido');
                } elseif ($log->status === 'partial') {
                    $message .= " parcialmente concluída";
                } elseif ($log->status === 'running') {
                    $message .= " em andamento";
                }

                $formattedLogs[] = [
                    'timestamp' => $log->started_at->format('d/m/Y H:i:s'),
                    'message' => $message,
                    'duration' => $log->duration_formatted,
                    'entity' => $log->entity,
                    'status' => $log->status
                ];
            }

            return $formattedLogs;
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar logs de sincronização: ' . $e->getMessage());
            return [];
        }
    }
}