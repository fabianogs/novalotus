<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class BannerApiController extends Controller
{
    /**
     * Lista todos os banners cadastrados
     * 
     * Parâmetros opcionais via query string:
     * - ativo: true/false (filtra por status ativo)
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Banner::query();
            
            // Filtro por status ativo
            if (request()->has('ativo')) {
                $ativo = filter_var(request('ativo'), FILTER_VALIDATE_BOOLEAN);
                $query->where('ativo', $ativo);
            }
            
            // Ordenação
            $query->orderBy('created_at', 'desc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 100) {
                    $query->limit($limit);
                }
            }
            
            $banners = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $banners,
                'count' => $banners->count(),
                'message' => 'Banners listados com sucesso'
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
     * Lista apenas banners ativos
     * 
     * Parâmetros opcionais via query string:
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function active(): JsonResponse
    {
        try {
            $query = Banner::where('ativo', true);
            
            // Ordenação
            $query->orderBy('created_at', 'desc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 100) {
                    $query->limit($limit);
                }
            }
            
            $banners = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $banners,
                'count' => $banners->count(),
                'message' => 'Banners ativos listados com sucesso'
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
     * Mostra um banner específico
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $banner = Banner::find($id);
            
            if (!$banner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $banner,
                'message' => 'Banner encontrado com sucesso'
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