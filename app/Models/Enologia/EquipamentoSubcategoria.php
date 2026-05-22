<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipamentoSubcategoria extends Model {
    protected $connection = 'enologia';
    protected $table = 'equipamentos_subcategorias';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function equipamentosSubcategoriasIdiomas()
    {
        return $this->hasMany(EquipamentoSubcategoriaIdioma::class);
    }

    public function categoria()
    {
        return $this->belongsTo(EquipamentoCategoria::class, 'equipamento_categoria_id');
    }

    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }
}