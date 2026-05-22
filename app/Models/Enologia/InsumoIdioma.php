<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsumoIdioma extends Model {
    protected $connection = 'enologia';
    protected $table = 'insumos_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}