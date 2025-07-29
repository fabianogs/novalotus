<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; // Added for API status check

class EspecialidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $especialidades = Especialidade::orderBy('descricao', 'asc')
                                      ->get();
        
        return view('especialidades.index', compact('especialidades'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialidade $especialidade)
    {
        $especialidade->load(['especialistas']);
        return view('especialidades.show', compact('especialidade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Especialidade $especialidade)
    {
        return view('especialidades.edit', compact('especialidade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especialidade $especialidade)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
        ], [
            'descricao.required' => 'A descrição da especialidade é obrigatória.',
            'descricao.string' => 'A descrição deve ser um texto válido.',
            'descricao.max' => 'A descrição deve ter no máximo 255 caracteres.',
        ]);

        // Gerar slug único se a descrição mudou
        $slug = $request->descricao === $especialidade->descricao 
            ? $especialidade->slug 
            : $this->generateUniqueSlug($request->descricao, $especialidade->id);

        $especialidade->update([
            'descricao' => $request->descricao,
            'slug' => $slug,
        ]);

        return redirect()->route('especialidades.index')
                        ->with('success', 'Especialidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialidade $especialidade)
    {
        try {
            // Verificar se a especialidade possui relacionamentos
            $especialistasCount = $especialidade->especialistas()->count();

            if ($especialistasCount > 0) {
                return redirect()->route('especialidades.index')
                                ->with('error', 'Não é possível excluir esta especialidade pois ela possui especialistas vinculados.');
            }

            $especialidade->delete();

            return redirect()->route('especialidades.index')
                            ->with('success', 'Especialidade excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('especialidades.index')
                            ->with('error', 'Erro ao excluir a especialidade. Tente novamente.');
        }
    }

    /**
     * Buscar especialidades por AJAX
     */
    public function buscar(Request $request)
    {
        $term = $request->get('term');
        
        $especialidades = Especialidade::where('descricao', 'LIKE', "%{$term}%")
                                      ->orWhere('slug', 'LIKE', "%{$term}%")
                                      ->orderBy('descricao')
                                      ->limit(10)
                                      ->get(['id', 'descricao', 'slug']);

        return response()->json($especialidades);
    }

    /**
     * Sincronizar especialidades da API
     */
    public function sync()
    {
        try {
            \Artisan::call('especialidades:sync');
            $output = \Artisan::output();
            
            return redirect()->route('especialidades.index')
                            ->with('success', 'Sincronização de especialidades iniciada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('especialidades.index')
                            ->with('error', 'Erro ao sincronizar especialidades: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard de sincronização
     */
    public function syncDashboard()
    {
        // Estatísticas locais
        $totalLocal = Especialidade::count();
        $ultimaAtualizacao = Especialidade::max('updated_at');
        $especialidadesHoje = Especialidade::whereDate('updated_at', today())->count();
        $especialidadesSemana = Especialidade::whereDate('updated_at', '>=', now()->subDays(7))->count();

        // Verificar API externa
        $apiStatus = $this->checkApiStatus();

        // Logs recentes
        $recentLogs = $this->getRecentSyncLogs();

        $stats = [
            'local' => [
                'total' => $totalLocal,
                'ultima_atualizacao' => $ultimaAtualizacao,
                'atualizadas_hoje' => $especialidadesHoje,
                'atualizadas_semana' => $especialidadesSemana,
            ],
            'api' => $apiStatus,
            'logs' => $recentLogs,
            'status' => $this->getOverallStatus($totalLocal, $apiStatus)
        ];

        return view('especialidades.sync-dashboard', compact('stats'));
    }

    /**
     * Executar sincronização via AJAX
     */
    public function syncAjax()
    {
        try {
            $startTime = now();
            
            \Artisan::call('especialidades:sync');
            $output = \Artisan::output();
            
            $duration = now()->diffInSeconds($startTime);
            
            return response()->json([
                'success' => true,
                'message' => 'Sincronização concluída com sucesso!',
                'duration' => $duration,
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao sincronizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar status da API
     */
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

    /**
     * Obter logs recentes de sincronização
     */
    private function getRecentSyncLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return [];
        }

        $logs = [];
        $lines = file($logFile);
        $syncLines = [];

        // Buscar linhas relacionadas à sincronização (últimas 100 linhas)
        $recentLines = array_slice($lines, -100);
        
        foreach ($recentLines as $line) {
            if (strpos($line, 'Sincronização') !== false || strpos($line, 'especialidades') !== false) {
                // Extrair timestamp do log (formato: [2024-01-01 12:00:00])
                if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                    $timestamp = $matches[1];
                    $syncLines[] = [
                        'timestamp' => $timestamp,
                        'message' => trim($line)
                    ];
                } else {
                    $syncLines[] = [
                        'timestamp' => now()->format('Y-m-d H:i:s'),
                        'message' => trim($line)
                    ];
                }
            }
        }

        return array_slice($syncLines, -10); // Últimas 10 linhas
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

        if ($totalLocal < 50) {
            return 'warning';
        }

        return 'ok';
    }

    /**
     * Gerar slug único baseado na descrição
     */
    private function generateUniqueSlug($descricao, $excludeId = null)
    {
        $baseSlug = Str::slug($descricao);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = Especialidade::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
