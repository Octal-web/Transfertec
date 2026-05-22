<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipamentoSubcategoriaIdioma extends Model {
    protected $connection = 'enologia';
    protected $table = 'equipamentos_subcategorias_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function equipamentoSubcategoria()
    {
        return $this->belongsTo(EquipamentoSubcategoria::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}