<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsumoSubcategoriaIdioma extends Model {
    protected $connection = 'enologia';
    protected $table = 'insumos_subcategorias_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function insumoSubcategoria()
    {
        return $this->belongsTo(InsumoSubcategoria::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}