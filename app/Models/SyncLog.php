<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    protected $fillable = [
        'entity',
        'status',
        'total_items',
        'created_items',
        'updated_items',
        'error_items',
        'error_message',
        'details',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'details' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Escopo para buscar logs por entidade
     */
    public function scopeForEntity($query, $entity)
    {
        return $query->where('entity', $entity);
    }

    /**
     * Escopo para buscar logs por status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Escopo para buscar logs recentes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('started_at', '>=', now()->subDays($days));
    }

    /**
     * Obter a duração da sincronização
     */
    public function getDurationAttribute()
    {
        if (!$this->finished_at) {
            return null;
        }
        
        return $this->started_at->diffInSeconds($this->finished_at);
    }

    /**
     * Obter a duração formatada
     */
    public function getDurationFormattedAttribute()
    {
        $duration = $this->duration;
        if ($duration === null) {
            return 'Em andamento';
        }
        
        if ($duration < 60) {
            return $duration . 's';
        }
        
        $minutes = floor($duration / 60);
        $seconds = $duration % 60;
        
        return $minutes . 'm ' . $seconds . 's';
    }

    /**
     * Obter estatísticas resumidas
     */
    public function getSummaryAttribute()
    {
        $summary = [];
        
        if ($this->created_items > 0) {
            $summary[] = $this->created_items . ' criados';
        }
        
        if ($this->updated_items > 0) {
            $summary[] = $this->updated_items . ' atualizados';
        }
        
        if ($this->error_items > 0) {
            $summary[] = $this->error_items . ' com erro';
        }
        
        return implode(', ', $summary);
    }
}
