<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Endereco extends Model
{
    protected $fillable = [
        'especialista_id',
        'uf',
        'cidade_nome',
        'cep',
        'bairro',
        'logradouro',
        'numero',
        'complemento',
    ];

    public function especialista(): BelongsTo
    {
        return $this->belongsTo(Especialista::class);
    }
}
