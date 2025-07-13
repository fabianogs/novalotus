<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'sintese',
        'imagem',
        'link',
        'link_pdf',
        'slug'
    ];
}
