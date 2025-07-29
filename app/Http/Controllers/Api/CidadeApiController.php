<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cidade;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class CidadeApiController extends Controller
{
    use ApiResponse;

    /**
     * Lista todas as cidades cadastradas
     * 
     * Parâmetros opcionais via query string:
     * - uf: string (filtra por estado)
     * - search: string (busca por nome da cidade)
     * - limit: número (limita quantidade de resultados)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Cidade::query();
            
            // Filtro por UF
            if (request()->has('uf')) {
                $uf = strtoupper(request('uf'));
                if (strlen($uf) === 2) {
                    $query->where('uf', $uf);
                }
            }
            
            // Busca por nome da cidade
            if (request()->has('search')) {
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('nome', 'LIKE', '%' . $search . '%')
                      ->orWhere('nome_completo', 'LIKE', '%' . $search . '%');
                });
            }
            
            // Ordenação por UF e nome
            $query->orderBy('uf', 'asc')->orderBy('nome', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 100) {
                    $query->limit($limit);
                }
            }
            
            $cidades = $query->get();
            
            return $this->successResponse($cidades, 'Cidades listadas com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Lista cidades agrupadas por UF
     *
     * @return JsonResponse
     */
    public function byUf(): JsonResponse
    {
        try {
            $cidades = Cidade::orderBy('uf', 'asc')
                ->orderBy('nome', 'asc')
                ->get()
                ->groupBy('uf');
            
            return $this->successResponse($cidades, 'Cidades agrupadas por UF listadas com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }

    /**
     * Lista todos os estados (UF) disponíveis
     *
     * @return JsonResponse
     */
    public function estados(): JsonResponse
    {
        try {
            $estados = Cidade::select('uf')
                ->distinct()
                ->orderBy('uf', 'asc')
                ->pluck('uf');
            
            return $this->successResponse($estados, 'Estados listados com sucesso');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erro interno do servidor', 500, $e->getMessage());
        }
    }
} 