<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model {
    protected $connection = 'enologia';
    protected $table = 'insumos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function insumosIdiomas()
    {
        return $this->hasMany(InsumoIdioma::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(InsumoSubcategoria::class, 'insumo_subcategoria_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}