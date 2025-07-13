<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Necessidade extends Model
{
    protected $fillable = [
        'titulo',
        'slug',
    ];

    public function especialistas(): HasMany
    {
        return $this->hasMany(Especialista::class);
    }

    public function parceiros(): HasMany
    {
        return $this->hasMany(Parceiro::class);
    }
}
