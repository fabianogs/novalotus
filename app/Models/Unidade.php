<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unidade extends Model
{
    protected $fillable = [
        'cidade_id',
        'telefone',
        'endereco',
        'imagem',
        'slug',
    ];

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }
}
