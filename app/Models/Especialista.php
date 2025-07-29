<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Especialista extends Model
{
    protected $fillable = [
        'foto',
        'nome',
        'nome_fantasia',
        'conselho',
        'registro',
        'registro_uf',
        'cidade_id',
        'endereco',
        'necessidade_id',
        'slug',
    ];

    public function especialidades(): BelongsToMany
    {
        return $this->belongsToMany(Especialidade::class, 'especialista_especialidade');
    }

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    public function necessidade(): BelongsTo
    {
        return $this->belongsTo(Necessidade::class);
    }

    public function enderecos(): HasMany
    {
        return $this->hasMany(Endereco::class);
    }

    public function telefones(): HasMany
    {
        return $this->hasMany(Telefone::class);
    }
}
