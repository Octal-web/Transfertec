<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depoimento extends Model {
    protected $connection = 'engenharia';
    protected $table = 'depoimentos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function depoimentosIdiomas()
    {
        return $this->hasMany(DepoimentoIdioma::class);
    }
}