<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cidade;
use Illuminate\Http\JsonResponse;

class CidadeApiController extends Controller
{
    /**
     * Lista todas as cidades cadastradas
     * 
     * Parâmetros opcionais via query string:
     * - uf: string (filtra por estado - ex: SP, RJ, MG)
     * - limit: número (limita quantidade de resultados)
     * - search: string (busca por nome da cidade)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $query = Cidade::query();
            
            // Filtro por UF (estado)
            if (request()->has('uf')) {
                $uf = strtoupper(request('uf'));
                if (strlen($uf) === 2) {
                    $query->where('uf', $uf);
                }
            }
            
            // Busca por nome da cidade
            if (request()->has('search')) {
                $search = request('search');
                $query->where('nome', 'LIKE', '%' . $search . '%');
            }
            
            // Ordenação alfabética por nome
            $query->orderBy('nome', 'asc');
            
            // Limite de resultados
            if (request()->has('limit')) {
                $limit = (int) request('limit');
                if ($limit > 0 && $limit <= 500) {
                    $query->limit($limit);
                }
            }
            
            $cidades = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $cidades,
                'count' => $cidades->count(),
                'message' => 'Cidades listadas com sucesso'
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
            
            return response()->json([
                'success' => true,
                'data' => $cidades,
                'message' => 'Cidades agrupadas por UF listadas com sucesso'
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
     * Lista apenas os estados (UFs) disponíveis
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
            
            return response()->json([
                'success' => true,
                'data' => $estados,
                'count' => $estados->count(),
                'message' => 'Estados listados com sucesso'
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