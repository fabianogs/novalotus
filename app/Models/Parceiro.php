<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parceiro extends Model
{
    protected $fillable = [
        'logo',
        'logo_carrossel',
        'nome',
        'descricao',
        'cidade_id',
        'endereco',
        'necessidade_id',
        'slug',
    ];

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    public function necessidade(): BelongsTo
    {
        return $this->belongsTo(Necessidade::class);
    }
}
