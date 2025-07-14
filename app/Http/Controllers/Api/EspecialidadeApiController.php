<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use Illuminate\Http\JsonResponse;

class EspecialidadeApiController extends Controller
{
    /**
     * Lista todas as especialidades cadastradas
     * 
     * Parâmetros opcionais via query string:
     * - search: string (busca por nome da especialidade)
     * - limit: número (limita quantidade de resultados)
     * - with_count: boolean (inclui contagem de especialistas)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Especialidade::query();
            
            // Busca por nome da especialidade
            if (request()->has('search')) {
                $search = request('search');
                $query->where('nome', 'LIKE', '%' . $search . '%');
            }
            
            // Incluir contagem de especialistas
            if (request()->has('with_count') && filter_var(request('with_count'), FILTER_VALIDATE_BOOLEAN)) {
                $query->withCount('especialistas');
            }
            
            // Ordenação alfabética por nome
            $query->orderBy('nome', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 200) {
                    $query->limit($limit);
                }
            }
            
            $especialidades = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $especialidades,
                'count' => $especialidades->count(),
                'message' => 'Especialidades listadas com sucesso'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno'
            ], 500);
        }
    }

    /**
     * Lista os especialistas de uma especialidade específica
     * 
     * Parâmetros opcionais via query string:
     * - cidade_id: integer (filtra por cidade)
     * - necessidade_id: integer (filtra por necessidade)
     * - limit: número (limita quantidade de resultados)
     *
     * @param int $especialidadeId
     * @return JsonResponse
     */
    public function especialistas($especialidadeId): JsonResponse
    {
        try {
            // Verificar se a especialidade existe
            $especialidade = Especialidade::find($especialidadeId);
            
            if (!$especialidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialidade não encontrada'
                ], 404);
            }
            
            $query = $especialidade->especialistas()
                ->with(['cidade', 'necessidade']);
            
            // Filtro por cidade
            if (request()->has('cidade_id')) {
                $cidadeId = (int) request('cidade_id');
                if ($cidadeId > 0) {
                    $query->where('cidade_id', $cidadeId);
                }
            }
            
            // Filtro por necessidade
            if (request()->has('necessidade_id')) {
                $necessidadeId = (int) request('necessidade_id');
                if ($necessidadeId > 0) {
                    $query->where('necessidade_id', $necessidadeId);
                }
            }
            
            // Ordenação por nome
            $query->orderBy('nome', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 100) {
                    $query->limit($limit);
                }
            }
            
            $especialistas = $query->get();
            
            return response()->json([
                'success' => true,
                'especialidade' => [
                    'id' => $especialidade->id,
                    'nome' => $especialidade->nome,
                    'slug' => $especialidade->slug
                ],
                'data' => $especialistas,
                'count' => $especialistas->count(),
                'message' => 'Especialistas da especialidade listados com sucesso'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno'
            ], 500);
        }
    }

    /**
     * Mostra uma especialidade específica
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $especialidade = Especialidade::withCount('especialistas')->find($id);
            
            if (!$especialidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialidade não encontrada'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $especialidade,
                'message' => 'Especialidade encontrada com sucesso'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno'
            ], 500);
        }
    }
} 