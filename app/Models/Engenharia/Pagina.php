<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagina extends Model {
    protected $connection = 'engenharia';
    protected $table = 'paginas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function paginasIdiomas()
    {
        return $this->hasMany(PaginaIdioma::class);
    }
}