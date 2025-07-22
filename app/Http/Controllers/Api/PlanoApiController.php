<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plano;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PlanoApiController extends Controller
{
    use ApiResponse;

    /**
     * Lista todos os planos cadastrados
     * 
     * Parâmetros opcionais via query string:
     * - search: string (busca por título do plano)
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Plano::query();
            
            // Busca por título do plano
            if (request()->has('search')) {
                $search = request('search');
                $query->where('titulo', 'LIKE', '%' . $search . '%');
            }
            
            // Ordenação alfabética por título
            $query->orderBy('titulo', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 100) {
                    $query->limit($limit);
                }
            }
            
            $planos = $query->get();
            
            return $this->successResponse($planos, 'Planos listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Lista planos de forma simplificada (apenas título e slug)
     *
     * @return JsonResponse
     */
    public function simple(): JsonResponse
    {
        try {
            $planos = Plano::select('id', 'titulo', 'slug')
                ->orderBy('titulo', 'asc')
                ->get();
            
            return $this->successResponse($planos, 'Planos simplificados listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Mostra um plano específico
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $plano = Plano::find($id);
            
            if (!$plano) {
                return $this->errorResponse('Plano não encontrado', 404);
            }
            
            return $this->successResponse($plano, 'Plano encontrado com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Busca planos por slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function findBySlug($slug): JsonResponse
    {
        try {
            $plano = Plano::where('slug', $slug)->first();
            
            if (!$plano) {
                return $this->errorResponse('Plano não encontrado', 404);
            }
            
            return $this->successResponse($plano, 'Plano encontrado com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }
} 