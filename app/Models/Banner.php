<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use Loggable;
    
    //
    protected $fillable = [
        'imagem',
        'titulo',
        'link',
        'ativo',
    ];
}
