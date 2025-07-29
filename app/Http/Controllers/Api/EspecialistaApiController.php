<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Especialista;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class EspecialistaApiController extends Controller
{
    use ApiResponse;

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
            $query = Especialista::with(['especialidades', 'cidade', 'necessidade']);
            
            // Filtro por especialidade
            if (request()->has('especialidade_id')) {
                $especialidadeId = (int) request('especialidade_id');
                if ($especialidadeId > 0) {
                    $query->whereHas('especialidades', function($q) use ($especialidadeId) {
                        $q->where('especialidades.id', $especialidadeId);
                    });
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
            
            return $this->successResponse($especialistas, 'Especialistas listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            $especialista = Especialista::with(['especialidades', 'cidade', 'necessidade'])->find($id);
            
            if (!$especialista) {
                return $this->errorResponse('Especialista não encontrado', 404);
            }
            
            return $this->successResponse($especialista, 'Especialista encontrado com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            $especialistas = Especialista::with(['especialidades', 'cidade', 'necessidade'])
                ->orderBy('nome', 'asc')
                ->get();
            
            // Agrupar por especialidades
            $grouped = [];
            foreach ($especialistas as $especialista) {
                foreach ($especialista->especialidades as $especialidade) {
                    if (!isset($grouped[$especialidade->nome])) {
                        $grouped[$especialidade->nome] = [];
                    }
                    $grouped[$especialidade->nome][] = $especialista;
                }
            }
            
            return $this->successResponse($grouped, 'Especialistas agrupados por especialidade listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            $especialistas = Especialista::with(['especialidades', 'cidade', 'necessidade'])
                ->orderBy('nome', 'asc')
                ->get()
                ->groupBy('cidade.nome');
            
            return $this->successResponse($especialistas, 'Especialistas agrupados por cidade listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            $especialista = Especialista::with(['especialidades', 'cidade', 'necessidade'])
                ->where('slug', $slug)
                ->first();
            
            if (!$especialista) {
                return $this->errorResponse('Especialista não encontrado', 404);
            }
            
            return $this->successResponse($especialista, 'Especialista encontrado com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }
} 