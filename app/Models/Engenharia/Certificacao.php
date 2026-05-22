<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificacao extends Model {
    protected $connection = 'engenharia';
    protected $table = 'certificacoes';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function certificacoesIdiomas()
    {
        return $this->hasMany(CertificacaoIdioma::class);
    }
}