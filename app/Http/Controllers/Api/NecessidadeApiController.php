<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Necessidade;
use Illuminate\Http\JsonResponse;

class NecessidadeApiController extends Controller
{
    /**
     * Lista todas as necessidades cadastradas
     * 
     * Parâmetros opcionais via query string:
     * - search: string (busca por título da necessidade)
     * - limit: número (limita quantidade de resultados)
     * - with_count: boolean (inclui contagem de especialistas e parceiros)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Necessidade::query();
            
            // Busca por título da necessidade
            if (request()->has('search')) {
                $search = request('search');
                $query->where('titulo', 'LIKE', '%' . $search . '%');
            }
            
            // Incluir contagem de especialistas e parceiros
            if (request()->has('with_count') && filter_var(request('with_count'), FILTER_VALIDATE_BOOLEAN)) {
                $query->withCount(['especialistas', 'parceiros']);
            }
            
            // Ordenação alfabética por título
            $query->orderBy('titulo', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 200) {
                    $query->limit($limit);
                }
            }
            
            $necessidades = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $necessidades,
                'count' => $necessidades->count(),
                'message' => 'Necessidades listadas com sucesso'
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
     * Mostra uma necessidade específica
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $necessidade = Necessidade::withCount(['especialistas', 'parceiros'])->find($id);
            
            if (!$necessidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Necessidade não encontrada'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $necessidade,
                'message' => 'Necessidade encontrada com sucesso'
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
     * Lista os especialistas de uma necessidade específica
     * 
     * Parâmetros opcionais via query string:
     * - especialidade_id: integer (filtra por especialidade)
     * - cidade_id: integer (filtra por cidade)
     * - limit: número (limita quantidade de resultados)
     *
     * @param int $necessidadeId
     * @return JsonResponse
     */
    public function especialistas($necessidadeId): JsonResponse
    {
        try {
            // Verificar se a necessidade existe
            $necessidade = Necessidade::find($necessidadeId);
            
            if (!$necessidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Necessidade não encontrada'
                ], 404);
            }
            
            $query = $necessidade->especialistas()
                ->with(['especialidade', 'cidade']);
            
            // Filtro por especialidade
            if (request()->has('especialidade_id')) {
                $especialidadeId = (int) request('especialidade_id');
                if ($especialidadeId > 0) {
                    $query->where('especialidade_id', $especialidadeId);
                }
            }
            
            // Filtro por cidade
            if (request()->has('cidade_id')) {
                $cidadeId = (int) request('cidade_id');
                if ($cidadeId > 0) {
                    $query->where('cidade_id', $cidadeId);
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
                'necessidade' => [
                    'id' => $necessidade->id,
                    'titulo' => $necessidade->titulo,
                    'slug' => $necessidade->slug
                ],
                'data' => $especialistas,
                'count' => $especialistas->count(),
                'message' => 'Especialistas da necessidade listados com sucesso'
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
     * Lista os parceiros de uma necessidade específica
     * 
     * Parâmetros opcionais via query string:
     * - cidade_id: integer (filtra por cidade)
     * - limit: número (limita quantidade de resultados)
     *
     * @param int $necessidadeId
     * @return JsonResponse
     */
    public function parceiros($necessidadeId): JsonResponse
    {
        try {
            // Verificar se a necessidade existe
            $necessidade = Necessidade::find($necessidadeId);
            
            if (!$necessidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Necessidade não encontrada'
                ], 404);
            }
            
            $query = $necessidade->parceiros()
                ->with(['cidade']);
            
            // Filtro por cidade
            if (request()->has('cidade_id')) {
                $cidadeId = (int) request('cidade_id');
                if ($cidadeId > 0) {
                    $query->where('cidade_id', $cidadeId);
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
            
            $parceiros = $query->get();
            
            return response()->json([
                'success' => true,
                'necessidade' => [
                    'id' => $necessidade->id,
                    'titulo' => $necessidade->titulo,
                    'slug' => $necessidade->slug
                ],
                'data' => $parceiros,
                'count' => $parceiros->count(),
                'message' => 'Parceiros da necessidade listados com sucesso'
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
     * Lista tanto especialistas quanto parceiros de uma necessidade específica
     * 
     * Parâmetros opcionais via query string:
     * - cidade_id: integer (filtra por cidade)
     * - especialidade_id: integer (filtra especialistas por especialidade)
     * - limit_especialistas: número (limita especialistas)
     * - limit_parceiros: número (limita parceiros)
     *
     * @param int $necessidadeId
     * @return JsonResponse
     */
    public function profissionais($necessidadeId): JsonResponse
    {
        try {
            // Verificar se a necessidade existe
            $necessidade = Necessidade::find($necessidadeId);
            
            if (!$necessidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Necessidade não encontrada'
                ], 404);
            }
            
            // Query para especialistas
            $queryEspecialistas = $necessidade->especialistas()
                ->with(['especialidade', 'cidade']);
            
            // Query para parceiros
            $queryParceiros = $necessidade->parceiros()
                ->with(['cidade']);
            
            // Filtros comuns por cidade
            if (request()->has('cidade_id')) {
                $cidadeId = (int) request('cidade_id');
                if ($cidadeId > 0) {
                    $queryEspecialistas->where('cidade_id', $cidadeId);
                    $queryParceiros->where('cidade_id', $cidadeId);
                }
            }
            
            // Filtro específico para especialistas por especialidade
            if (request()->has('especialidade_id')) {
                $especialidadeId = (int) request('especialidade_id');
                if ($especialidadeId > 0) {
                    $queryEspecialistas->where('especialidade_id', $especialidadeId);
                }
            }
            
            // Ordenação
            $queryEspecialistas->orderBy('nome', 'asc');
            $queryParceiros->orderBy('nome', 'asc');
            
            // Limites específicos
            if (request()->has('limit_especialistas')) {
                $limit = (int) request('limit_especialistas');
                if ($limit > 0 && $limit <= 50) {
                    $queryEspecialistas->limit($limit);
                }
            }
            
            if (request()->has('limit_parceiros')) {
                $limit = (int) request('limit_parceiros');
                if ($limit > 0 && $limit <= 50) {
                    $queryParceiros->limit($limit);
                }
            }
            
            $especialistas = $queryEspecialistas->get();
            $parceiros = $queryParceiros->get();
            
            return response()->json([
                'success' => true,
                'necessidade' => [
                    'id' => $necessidade->id,
                    'titulo' => $necessidade->titulo,
                    'slug' => $necessidade->slug
                ],
                'data' => [
                    'especialistas' => $especialistas,
                    'parceiros' => $parceiros
                ],
                'count' => [
                    'especialistas' => $especialistas->count(),
                    'parceiros' => $parceiros->count(),
                    'total' => $especialistas->count() + $parceiros->count()
                ],
                'message' => 'Profissionais da necessidade listados com sucesso'
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