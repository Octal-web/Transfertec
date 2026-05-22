<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solucao extends Model {
    protected $connection = 'enologia';
    protected $table = 'solucoes';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function solucoesIdiomas()
    {
        return $this->hasMany(SolucaoIdioma::class);
    }
}