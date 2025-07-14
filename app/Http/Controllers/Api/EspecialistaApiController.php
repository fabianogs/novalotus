<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Especialista;
use Illuminate\Http\JsonResponse;

class EspecialistaApiController extends Controller
{
    /**
     * Lista todos os especialistas cadastrados
     * 
     * Parâmetros opcionais via query string:
     * - especialidade_id: integer (filtra por especialidade)
     * - cidade_id: integer (filtra por cidade)
     * - necessidade_id: integer (filtra por necessidade)
     * - search: string (busca por nome do especialista)
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Especialista::with(['especialidade', 'cidade', 'necessidade']);
            
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
            
            // Filtro por necessidade
            if (request()->has('necessidade_id')) {
                $necessidadeId = (int) request('necessidade_id');
                if ($necessidadeId > 0) {
                    $query->where('necessidade_id', $necessidadeId);
                }
            }
            
            // Busca por nome do especialista
            if (request()->has('search')) {
                $search = request('search');
                $query->where('nome', 'LIKE', '%' . $search . '%');
            }
            
            // Ordenação alfabética por nome
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
                'data' => $especialistas,
                'count' => $especialistas->count(),
                'message' => 'Especialistas listados com sucesso'
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
     * Mostra um especialista específico
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $especialista = Especialista::with(['especialidade', 'cidade', 'necessidade'])->find($id);
            
            if (!$especialista) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialista não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $especialista,
                'message' => 'Especialista encontrado com sucesso'
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
     * Lista especialistas agrupados por especialidade
     *
     * @return JsonResponse
     */
    public function byEspecialidade(): JsonResponse
    {
        try {
            $especialistas = Especialista::with(['especialidade', 'cidade', 'necessidade'])
                ->orderBy('nome', 'asc')
                ->get()
                ->groupBy('especialidade.nome');
            
            return response()->json([
                'success' => true,
                'data' => $especialistas,
                'message' => 'Especialistas agrupados por especialidade listados com sucesso'
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
     * Lista especialistas agrupados por cidade
     *
     * @return JsonResponse
     */
    public function byCidade(): JsonResponse
    {
        try {
            $especialistas = Especialista::with(['especialidade', 'cidade', 'necessidade'])
                ->orderBy('nome', 'asc')
                ->get()
                ->groupBy('cidade.nome');
            
            return response()->json([
                'success' => true,
                'data' => $especialistas,
                'message' => 'Especialistas agrupados por cidade listados com sucesso'
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
     * Busca especialistas por slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function findBySlug($slug): JsonResponse
    {
        try {
            $especialista = Especialista::with(['especialidade', 'cidade', 'necessidade'])
                ->where('slug', $slug)
                ->first();
            
            if (!$especialista) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialista não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $especialista,
                'message' => 'Especialista encontrado com sucesso'
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