<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlanoApiController extends Controller
{
    /**
     * Lista todos os planos com filtros opcionais
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Plano::query();

            // Filtro por busca (titulo ou descricao)
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('titulo', 'like', '%' . $search . '%')
                      ->orWhere('descricao', 'like', '%' . $search . '%')
                      ->orWhere('sintese', 'like', '%' . $search . '%');
                });
            }

            // Limite de resultados
            $limit = $request->get('limit', 10);
            if ($limit > 100) $limit = 100; // Limite máximo
            
            $planos = $query->paginate($limit);

            return response()->json([
                'success' => true,
                'data' => $planos->items(),
                'count' => $planos->count(),
                'total' => $planos->total(),
                'current_page' => $planos->currentPage(),
                'last_page' => $planos->lastPage(),
                'message' => 'Planos listados com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe um plano específico
     */
    public function show($id): JsonResponse
    {
        try {
            $plano = Plano::find($id);

            if (!$plano) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plano não encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $plano,
                'message' => 'Plano encontrado com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca plano por slug
     */
    public function findBySlug($slug): JsonResponse
    {
        try {
            $plano = Plano::where('slug', $slug)->first();

            if (!$plano) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plano não encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $plano,
                'message' => 'Plano encontrado com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista planos simples (apenas titulo e slug)
     */
    public function simple(): JsonResponse
    {
        try {
            $planos = Plano::select('id', 'titulo', 'slug', 'imagem')->get();

            return response()->json([
                'success' => true,
                'data' => $planos,
                'count' => $planos->count(),
                'message' => 'Lista simples de planos'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 