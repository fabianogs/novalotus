<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parceiro;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ParceiroApiController extends Controller
{
    use ApiResponse;

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
            
            return $this->successResponse($parceiros, 'Parceiros listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
                return $this->errorResponse('Parceiro não encontrado', 404);
            }
            
            return $this->successResponse($parceiro, 'Parceiro encontrado com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            
            return $this->successResponse($parceiros, 'Parceiros agrupados por necessidade listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            
            return $this->successResponse($parceiros, 'Parceiros agrupados por cidade listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
                return $this->errorResponse('Parceiro não encontrado', 404);
            }
            
            return $this->successResponse($parceiro, 'Parceiro encontrado com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            
            return $this->successResponse($parceiros, 'Parceiros agrupados por estado listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
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
            
            return $this->successResponse($parceiros, 'Parceiros para carrossel listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }
} 