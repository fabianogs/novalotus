<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

trait ApiResponse
{
    /**
     * Converte URLs relativas de imagens para URLs absolutas e remove campos de status
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
                // Remove campos de status ativo
                if ($this->isStatusField($key)) {
                    unset($data[$key]);
                    continue;
                }
                
                if (is_string($value) && $this->isImageField($key) && !empty($value)) {
                    $data[$key] = $this->getAbsoluteUrl($value, $key);
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
     * Verifica se o campo é um campo de status ativo
     *
     * @param string $fieldName
     * @return bool
     */
    protected function isStatusField(string $fieldName): bool
    {
        $statusFields = ['ativo', 'status'];
        return in_array($fieldName, $statusFields);
    }

    /**
     * Converte URL relativa para absoluta
     *
     * @param string $url
     * @param string $fieldName
     * @return string
     */
    protected function getAbsoluteUrl(string $url, string $fieldName = ''): string
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

        // Se é apenas um nome de arquivo, adiciona o caminho baseado no tipo de campo
        if (!str_contains($url, '/')) {
            $basePath = $this->getImageBasePath($fieldName);
            return URL::to($basePath . $url);
        }

        // Para outros casos, retorna a URL completa
        return URL::to($url);
    }

    /**
     * Retorna o caminho base para cada tipo de campo de imagem
     *
     * @param string $fieldName
     * @return string
     */
    protected function getImageBasePath(string $fieldName): string
    {
        $paths = [
            'imagem' => 'storage/img/banners/',
            'foto' => 'storage/img/especialistas/',
            'logo' => 'storage/img/parceiros/',
            'logo_carrossel' => 'storage/img/parceiros/',
        ];

        return $paths[$fieldName] ?? 'storage/';
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