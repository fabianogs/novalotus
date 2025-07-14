<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parceiro;
use Illuminate\Http\JsonResponse;

class ParceiroApiController extends Controller
{
    /**
     * Lista todos os parceiros cadastrados
     * 
     * Parâmetros opcionais via query string:
     * - necessidade_id: integer (filtra por necessidade)
     * - cidade_id: integer (filtra por cidade)
     * - search: string (busca por nome do parceiro)
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Parceiro::with(['cidade', 'necessidade']);
            
            // Filtro por necessidade
            if (request()->has('necessidade_id')) {
                $necessidadeId = (int) request('necessidade_id');
                if ($necessidadeId > 0) {
                    $query->where('necessidade_id', $necessidadeId);
                }
            }
            
            // Filtro por cidade
            if (request()->has('cidade_id')) {
                $cidadeId = (int) request('cidade_id');
                if ($cidadeId > 0) {
                    $query->where('cidade_id', $cidadeId);
                }
            }
            
            // Busca por nome do parceiro
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
            
            $parceiros = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $parceiros,
                'count' => $parceiros->count(),
                'message' => 'Parceiros listados com sucesso'
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
     * Mostra um parceiro específico
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $parceiro = Parceiro::with(['cidade', 'necessidade'])->find($id);
            
            if (!$parceiro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parceiro não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $parceiro,
                'message' => 'Parceiro encontrado com sucesso'
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
     * Lista parceiros agrupados por necessidade
     *
     * @return JsonResponse
     */
    public function byNecessidade(): JsonResponse
    {
        try {
            $parceiros = Parceiro::with(['cidade', 'necessidade'])
                ->orderBy('nome', 'asc')
                ->get()
                ->groupBy('necessidade.titulo');
            
            return response()->json([
                'success' => true,
                'data' => $parceiros,
                'message' => 'Parceiros agrupados por necessidade listados com sucesso'
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
     * Lista parceiros agrupados por cidade
     *
     * @return JsonResponse
     */
    public function byCidade(): JsonResponse
    {
        try {
            $parceiros = Parceiro::with(['cidade', 'necessidade'])
                ->orderBy('nome', 'asc')
                ->get()
                ->groupBy('cidade.nome');
            
            return response()->json([
                'success' => true,
                'data' => $parceiros,
                'message' => 'Parceiros agrupados por cidade listados com sucesso'
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
     * Busca parceiros por slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function findBySlug($slug): JsonResponse
    {
        try {
            $parceiro = Parceiro::with(['cidade', 'necessidade'])
                ->where('slug', $slug)
                ->first();
            
            if (!$parceiro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parceiro não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $parceiro,
                'message' => 'Parceiro encontrado com sucesso'
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
     * Lista parceiros por estado (UF)
     * 
     * Parâmetros opcionais via query string:
     * - uf: string (filtra por estado específico)
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function byEstado(): JsonResponse
    {
        try {
            $query = Parceiro::with(['cidade', 'necessidade'])
                ->join('cidades', 'parceiros.cidade_id', '=', 'cidades.id');
            
            // Filtro por UF específico
            if (request()->has('uf')) {
                $uf = strtoupper(request('uf'));
                if (strlen($uf) === 2) {
                    $query->where('cidades.uf', $uf);
                }
            }
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 100) {
                    $query->limit($limit);
                }
            }
            
            $query->select('parceiros.*')
                ->orderBy('cidades.uf', 'asc')
                ->orderBy('parceiros.nome', 'asc');
            
            $parceiros = $query->get()->groupBy('cidade.uf');
            
            return response()->json([
                'success' => true,
                'data' => $parceiros,
                'message' => 'Parceiros agrupados por estado listados com sucesso'
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
     * Lista apenas parceiros que possuem logo para carrossel
     *
     * @return JsonResponse
     */
    public function carrossel(): JsonResponse
    {
        try {
            $query = Parceiro::with(['cidade', 'necessidade'])
                ->whereNotNull('logo_carrossel')
                ->orderBy('nome', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 50) {
                    $query->limit($limit);
                }
            }
            
            $parceiros = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $parceiros,
                'count' => $parceiros->count(),
                'message' => 'Parceiros para carrossel listados com sucesso'
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