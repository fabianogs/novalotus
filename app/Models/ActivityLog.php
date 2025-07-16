<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar por ação
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para filtrar por modelo
     */
    public function scopeByModel($query, $model)
    {
        return $query->where('model', $model);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por período
     */
    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Retorna o nome amigável da ação
     */
    public function getActionNameAttribute(): string
    {
        return match($this->action) {
            'create' => 'Criado',
            'update' => 'Atualizado',
            'delete' => 'Excluído',
            'login' => 'Login',
            'logout' => 'Logout',
            default => ucfirst($this->action)
        };
    }

    /**
     * Retorna o nome amigável do modelo
     */
    public function getModelNameAttribute(): string
    {
        return match($this->model) {
            'Sobre' => 'Sobre',
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
            default => $this->model ?? 'Sistema'
        };
    }
}
