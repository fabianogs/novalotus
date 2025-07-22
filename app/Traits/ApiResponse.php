<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

trait ApiResponse
{
    /**
     * Converte URLs relativas de imagens para URLs absolutas
     *
     * @param mixed $data
     * @return mixed
     */
    protected function convertImageUrls($data)
    {
        if (is_array($data) || is_object($data)) {
            $data = collect($data)->map(function ($item) {
                return $this->convertImageUrls($item);
            })->toArray();
        }

        if (is_object($data)) {
            $data = (array) $data;
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_string($value) && $this->isImageField($key) && !empty($value)) {
                    $data[$key] = $this->getAbsoluteUrl($value);
                } elseif (is_array($value) || is_object($value)) {
                    $data[$key] = $this->convertImageUrls($value);
                }
            }
        }

        return $data;
    }

    /**
     * Verifica se o campo é um campo de imagem
     *
     * @param string $fieldName
     * @return bool
     */
    protected function isImageField(string $fieldName): bool
    {
        $imageFields = ['imagem', 'foto', 'logo', 'logo_carrossel'];
        return in_array($fieldName, $imageFields);
    }

    /**
     * Converte URL relativa para absoluta
     *
     * @param string $url
     * @return string
     */
    protected function getAbsoluteUrl(string $url): string
    {
        // Se já é uma URL absoluta, retorna como está
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        // Remove barras iniciais se existirem
        $url = ltrim($url, '/');

        // Se a URL começa com 'storage/', converte para URL completa
        if (str_starts_with($url, 'storage/')) {
            return URL::to($url);
        }

        // Se é apenas um nome de arquivo, assume que está em storage/app/public
        if (!str_contains($url, '/')) {
            return URL::to('storage/' . $url);
        }

        // Para outros casos, retorna a URL completa
        return URL::to($url);
    }

    /**
     * Aplica filtro de status ativo na query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $statusField
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyActiveFilter($query, string $statusField = 'ativo')
    {
        // Por padrão, sempre filtra apenas registros ativos
        return $query->where($statusField, true);
    }

    /**
     * Resposta de sucesso padronizada
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function successResponse($data, string $message = 'Sucesso', int $status = 200): JsonResponse
    {
        $data = $this->convertImageUrls($data);
        
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message
        ];

        // Adiciona count se for uma coleção
        if (is_array($data) || (is_object($data) && method_exists($data, 'count'))) {
            $response['count'] = is_array($data) ? count($data) : $data->count();
        }

        return response()->json($response, $status);
    }

    /**
     * Resposta de erro padronizada
     *
     * @param string $message
     * @param int $status
     * @param mixed $error
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Erro interno do servidor', int $status = 500, $error = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($error && config('app.debug')) {
            $response['error'] = $error;
        }

        return response()->json($response, $status);
    }
} 