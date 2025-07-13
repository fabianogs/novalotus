<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Config extends Model
{
    protected $table = 'config';

    protected $fillable = [
        'unidade_id',
        'celular',
        'fone1',
        'fone2',
        'email',
        'endereco',
        'whatsapp',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'maps',
        'form_email_to',
        'form_email_cc',
        'form_email_to',
        'email_port',
        'email_username',
        'email_password',
        'email_host',
        'texto_contrato',
        'cnpj',
        'expediente',
        'razao_social',
        'arquivo_lgpd',
        'texto_lgpd'
    ];

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }
}
