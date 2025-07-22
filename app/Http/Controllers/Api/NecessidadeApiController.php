<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Necessidade;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class NecessidadeApiController extends Controller
{
    use ApiResponse;

    /**
     * Lista todas as necessidades cadastradas
     * 
     * Parâmetros opcionais via query string:
     * - search: string (busca por título da necessidade)
     * - limit: número (limita quantidade de resultados)
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
            
            // Ordenação alfabética por título
            $query->orderBy('titulo', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 100) {
                    $query->limit($limit);
                }
            }
            
            $necessidades = $query->get();
            
            return $this->successResponse($necessidades, 'Necessidades listadas com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            $necessidade = Necessidade::find($id);
            
            if (!$necessidade) {
                return $this->errorResponse('Necessidade não encontrada', 404);
            }
            
            return $this->successResponse($necessidade, 'Necessidade encontrada com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Lista especialistas de uma necessidade específica
     *
     * @param int $id
     * @return JsonResponse
     */
    public function especialistas($id): JsonResponse
    {
        try {
            $necessidade = Necessidade::with(['especialistas' => function($query) {
                $query->with(['cidade', 'especialidade'])->orderBy('nome', 'asc');
            }])->find($id);
            
            if (!$necessidade) {
                return $this->errorResponse('Necessidade não encontrada', 404);
            }
            
            return $this->successResponse($necessidade->especialistas, 'Especialistas da necessidade listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Lista parceiros de uma necessidade específica
     *
     * @param int $id
     * @return JsonResponse
     */
    public function parceiros($id): JsonResponse
    {
        try {
            $necessidade = Necessidade::with(['parceiros' => function($query) {
                $query->with(['cidade'])->orderBy('nome', 'asc');
            }])->find($id);
            
            if (!$necessidade) {
                return $this->errorResponse('Necessidade não encontrada', 404);
            }
            
            return $this->successResponse($necessidade->parceiros, 'Parceiros da necessidade listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Lista profissionais (especialistas e parceiros) de uma necessidade específica
     *
     * @param int $id
     * @return JsonResponse
     */
    public function profissionais($id): JsonResponse
    {
        try {
            $necessidade = Necessidade::with([
                'especialistas' => function($query) {
                    $query->with(['cidade', 'especialidade'])->orderBy('nome', 'asc');
                },
                'parceiros' => function($query) {
                    $query->with(['cidade'])->orderBy('nome', 'asc');
                }
            ])->find($id);
            
            if (!$necessidade) {
                return $this->errorResponse('Necessidade não encontrada', 404);
            }
            
            $profissionais = [
                'especialistas' => $necessidade->especialistas,
                'parceiros' => $necessidade->parceiros
            ];
            
            return $this->successResponse($profissionais, 'Profissionais da necessidade listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }
} 