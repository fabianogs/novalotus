<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class CidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cidades = Cidade::withCount(['especialistas', 'parceiros', 'unidades'])
                         ->orderBy('nome', 'asc')
                         ->get();
        
        return view('cidades.index', compact('cidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'uf' => 'required|string|size:2'
        ], [
            'nome.required' => 'O nome da cidade é obrigatório.',
            'uf.required' => 'O estado (UF) é obrigatório.',
            'uf.size' => 'O estado deve ter exatamente 2 caracteres.'
        ]);

        // Gerar slug único
        $slug = $this->generateUniqueSlug($request->nome);

        // Criar a cidade
        Cidade::create([
            'nome' => $request->nome,
            'slug' => $slug,
            'uf' => strtoupper($request->uf)
        ]);

        return redirect()->route('cidades.index')
                        ->with('success', 'Cidade criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cidade $cidade)
    {
        $cidade->load(['especialistas', 'parceiros', 'unidades']);
        return view('cidades.show', compact('cidade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cidade $cidade)
    {
        $cidade->loadCount(['especialistas', 'parceiros', 'unidades']);
        return view('cidades.edit', compact('cidade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cidade $cidade)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'nome_completo' => 'nullable|string|max:255'
        ], [
            'nome.required' => 'O nome da cidade é obrigatório.',
            'uf.required' => 'O estado (UF) é obrigatório.',
            'uf.size' => 'O estado deve ter exatamente 2 caracteres.'
        ]);

        // Gerar slug único se o nome mudou
        $slug = $request->nome === $cidade->nome 
            ? $cidade->slug 
            : $this->generateUniqueSlug($request->nome, $cidade->id);

        $cidade->update([
            'nome' => $request->nome,
            'slug' => $slug,
            'uf' => strtoupper($request->uf),
            'nome_completo' => $request->nome_completo
        ]);

        return redirect()->route('cidades.index')
                        ->with('success', 'Cidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cidade $cidade)
    {
        try {
            // Verificar se a cidade possui relacionamentos
            $especialistasCount = $cidade->especialistas()->count();
            $parceirosCount = $cidade->parceiros()->count();
            $unidadesCount = $cidade->unidades()->count();

            if ($especialistasCount > 0 || $parceirosCount > 0 || $unidadesCount > 0) {
                return redirect()->route('cidades.index')
                                ->with('error', 'Não é possível excluir esta cidade pois ela possui especialistas, parceiros ou unidades vinculadas.');
            }

            $cidade->delete();

            return redirect()->route('cidades.index')
                            ->with('success', 'Cidade excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('cidades.index')
                            ->with('error', 'Erro ao excluir a cidade. Tente novamente.');
        }
    }

    /**
     * Buscar cidades por AJAX
     */
    public function buscar(Request $request)
    {
        $term = $request->get('term');
        
        $cidades = Cidade::where('nome', 'LIKE', "%{$term}%")
                        ->orWhere('slug', 'LIKE', "%{$term}%")
                        ->orWhere('uf', 'LIKE', "%{$term}%")
                        ->orWhere('nome_completo', 'LIKE', "%{$term}%")
                        ->orderBy('nome')
                        ->limit(10)
                        ->get(['id', 'nome', 'uf', 'slug', 'nome_completo']);

        return response()->json($cidades);
    }

    /**
     * Sincronizar cidades da API
     */
    public function sync()
    {
        try {
            \Artisan::call('cidades:sync');
            $output = \Artisan::output();
            
            return redirect()->route('cidades.index')
                            ->with('success', 'Sincronização de cidades iniciada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('cidades.index')
                            ->with('error', 'Erro ao sincronizar cidades: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard de sincronização
     */
    public function syncDashboard()
    {
        // Estatísticas locais
        $totalLocal = Cidade::count();
        $ultimaAtualizacao = Cidade::max('updated_at');
        $cidadesHoje = Cidade::whereDate('updated_at', today())->count();
        $cidadesSemana = Cidade::whereDate('updated_at', '>=', now()->subDays(7))->count();

        // Verificar API externa
        $apiStatus = $this->checkApiStatus();

        // Logs recentes
        $recentLogs = $this->getRecentSyncLogs();

        $stats = [
            'local' => [
                'total' => $totalLocal,
                'ultima_atualizacao' => $ultimaAtualizacao,
                'atualizadas_hoje' => $cidadesHoje,
                'atualizadas_semana' => $cidadesSemana,
            ],
            'api' => $apiStatus,
            'logs' => $recentLogs,
            'status' => $this->getOverallStatus($totalLocal, $apiStatus)
        ];

        return view('cidades.sync-dashboard', compact('stats'));
    }

    /**
     * Executar sincronização via AJAX
     */
    public function syncAjax()
    {
        try {
            $startTime = now();
            
            \Artisan::call('cidades:sync');
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
            if (strpos($line, 'Sincronização') !== false || strpos($line, 'cidades') !== false) {
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

        if ($totalLocal < 5) {
            return 'warning';
        }

        return 'ok';
    }

    /**
     * Gerar slug único baseado no nome
     */
    private function generateUniqueSlug($nome, $excludeId = null)
    {
        $baseSlug = Str::slug($nome);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = Cidade::where('slug', $slug);
            
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
