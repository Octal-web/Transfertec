<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsumoCategoria extends Model {
    protected $connection = 'enologia';
    protected $table = 'insumos_categorias';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function insumosCategoriasIdiomas()
    {
        return $this->hasMany(InsumoCategoriaIdioma::class);
    }

    public function insumosSubcategorias()
    {
        return $this->hasMany(InsumoSubcategoria::class);
    }
}