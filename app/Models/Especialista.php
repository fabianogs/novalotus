<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Especialista extends Model
{
    protected $fillable = [
        'foto',
        'nome',
        'conselho',
        'especialidade_id',
        'cidade_id',
        'endereco',
        'necessidade_id',
        'slug',
    ];

    public function especialidade(): BelongsTo
    {
        return $this->belongsTo(Especialidade::class);
    }

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    public function necessidade(): BelongsTo
    {
        return $this->belongsTo(Necessidade::class);
    }
}
