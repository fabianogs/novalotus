<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Telefone extends Model
{
    protected $fillable = [
        'especialista_id',
        'numero',
        'observacao',
    ];

    public function especialista(): BelongsTo
    {
        return $this->belongsTo(Especialista::class);
    }
}
