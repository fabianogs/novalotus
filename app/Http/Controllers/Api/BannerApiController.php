<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class BannerApiController extends Controller
{
    use ApiResponse;

    /**
     * Lista todos os banners ativos cadastrados
     * 
     * Parâmetros opcionais via query string:
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Banner::query();
            
            // Sempre filtra apenas banners ativos
            $query = $this->applyActiveFilter($query);
            
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
            
            return $this->successResponse($banners, 'Banners listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Lista apenas banners ativos (método mantido para compatibilidade)
     * 
     * Parâmetros opcionais via query string:
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function active(): JsonResponse
    {
        try {
            $query = Banner::query();
            
            // Sempre filtra apenas banners ativos
            $query = $this->applyActiveFilter($query);
            
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
            
            return $this->successResponse($banners, 'Banners ativos listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Mostra um banner específico (apenas se estiver ativo)
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $banner = Banner::where('ativo', true)->find($id);
            
            if (!$banner) {
                return $this->errorResponse('Banner não encontrado', 404);
            }
            
            return $this->successResponse($banner, 'Banner encontrado com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }
} 