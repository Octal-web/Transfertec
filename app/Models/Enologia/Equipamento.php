<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model {
    protected $connection = 'enologia';
    protected $table = 'equipamentos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function equipamentosIdiomas()
    {
        return $this->hasMany(EquipamentoIdioma::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(EquipamentoSubcategoria::class, 'equipamento_subcategoria_id');
    }
}