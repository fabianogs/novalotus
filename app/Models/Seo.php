<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seo';
    protected $fillable = ['tipo', 'script', 'status', 'nome'];
}
