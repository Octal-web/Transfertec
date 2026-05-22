<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model {
    protected $connection = 'engenharia';
    protected $table = 'produtos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function produtosIdiomas()
    {
        return $this->hasMany(ProdutoIdioma::class);
    }

    public function casesClientes()
    {
        return $this->hasMany(CaseCliente::class);
    }
}