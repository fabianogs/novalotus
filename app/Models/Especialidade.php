<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Especialidade extends Model
{
    protected $fillable = [
        'nome',
        'slug',
    ];

    public function especialistas(): HasMany
    {
        return $this->hasMany(Especialista::class);
    }
}
