<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipamentoCategoria extends Model {
    protected $connection = 'enologia';
    protected $table = 'equipamentos_categorias';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function equipamentosCategoriasIdiomas()
    {
        return $this->hasMany(EquipamentoCategoriaIdioma::class);
    }

    public function equipamentosSubcategorias()
    {
        return $this->hasMany(EquipamentoSubcategoria::class);
    }
}