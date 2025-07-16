<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Registra uma ação CRUD
     */
    public static function logCrudAction(string $action, Model $model, ?array $oldValues = null, ?array $newValues = null): void
    {
        $description = self::generateDescription($action, $model);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => class_basename($model),
            'model_id' => $model->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ]);
    }

    /**
     * Registra login
     */
    public static function logLogin(): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'login',
            'description' => 'Usuário fez login no sistema',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ]);
    }

    /**
     * Registra logout
     */
    public static function logLogout(): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'logout',
            'description' => 'Usuário fez logout do sistema',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ]);
    }

    /**
     * Gera descrição da ação
     */
    private static function generateDescription(string $action, Model $model): string
    {
        $modelName = self::getModelDisplayName($model);
        
        return match($action) {
            'create' => "Novo {$modelName} criado",
            'update' => "{$modelName} atualizado",
            'delete' => "{$modelName} excluído",
            default => "Ação '{$action}' executada em {$modelName}"
        };
    }

    /**
     * Retorna nome de exibição do modelo
     */
    private static function getModelDisplayName(Model $model): string
    {
        $modelName = class_basename($model);
        
        return match($modelName) {
            'Sobre' => 'registro Sobre',
            'Banner' => 'Banner',
            'Cidade' => 'Cidade',
            'Especialidade' => 'Especialidade',
            'Especialista' => 'Especialista',
            'Necessidade' => 'Necessidade',
            'Parceiro' => 'Parceiro',
            'Plano' => 'Plano',
            'Unidade' => 'Unidade',
            'Config' => 'Configuração',
            'Seo' => 'SEO',
            default => $modelName
        };
    }
} 