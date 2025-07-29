<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class EspecialidadeApiController extends Controller
{
    use ApiResponse;

    /**
     * Lista todas as especialidades cadastradas
     * 
     * Parâmetros opcionais via query string:
     * - search: string (busca por nome da especialidade)
     * - limit: número (limita quantidade de resultados)
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
                $query->where('descricao', 'LIKE', '%' . $search . '%');
            }
            
            // Ordenação alfabética por descrição
            $query->orderBy('descricao', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 100) {
                    $query->limit($limit);
                }
            }
            
            $especialidades = $query->get();
            
            return $this->successResponse($especialidades, 'Especialidades listadas com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            $especialidade = Especialidade::find($id);
            
            if (!$especialidade) {
                return $this->errorResponse('Especialidade não encontrada', 404);
            }
            
            return $this->successResponse($especialidade, 'Especialidade encontrada com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Lista especialistas de uma especialidade específica
     *
     * @param int $id
     * @return JsonResponse
     */
    public function especialistas($id): JsonResponse
    {
        try {
            $especialidade = Especialidade::with(['especialistas' => function($query) {
                $query->with(['cidade', 'necessidade'])->orderBy('nome', 'asc');
            }])->find($id);
            
            if (!$especialidade) {
                return $this->errorResponse('Especialidade não encontrada', 404);
            }
            
            return $this->successResponse($especialidade->especialistas, 'Especialistas da especialidade listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }
} 