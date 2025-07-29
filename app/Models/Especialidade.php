<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Especialidade extends Model
{
    protected $fillable = [
        'id',
        'descricao',
        'slug',
    ];

    public $incrementing = false;
    protected $keyType = 'integer';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($especialidade) {
            if (empty($especialidade->slug)) {
                $especialidade->slug = Str::slug($especialidade->descricao);
            }
        });
    }

    public function especialistas(): BelongsToMany
    {
        return $this->belongsToMany(Especialista::class, 'especialista_especialidade');
    }

    public function getNomeAttribute()
    {
        return $this->descricao;
    }
}
