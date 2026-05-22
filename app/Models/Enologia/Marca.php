<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model {
    protected $connection = 'enologia';
    protected $table = 'marcas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }
}