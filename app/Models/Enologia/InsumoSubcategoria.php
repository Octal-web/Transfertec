<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsumoSubcategoria extends Model {
    protected $connection = 'enologia';
    protected $table = 'insumos_subcategorias';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function insumosSubcategoriasIdiomas()
    {
        return $this->hasMany(InsumoSubcategoriaIdioma::class);
    }

    public function categoria()
    {
        return $this->belongsTo(InsumoCategoria::class, 'insumo_categoria_id');
    }

    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }
}