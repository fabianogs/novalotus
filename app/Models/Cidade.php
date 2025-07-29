<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cidade extends Model
{
    protected $fillable = [
        'nome',
        'slug',
        'uf',
        'nome_completo',
    ];

    public function especialistas(): HasMany
    {
        return $this->hasMany(Especialista::class);
    }

    public function parceiros(): HasMany
    {
        return $this->hasMany(Parceiro::class);
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class);
    }
}
