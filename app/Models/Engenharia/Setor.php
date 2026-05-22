<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model {
    protected $connection = 'engenharia';
    protected $table = 'setores';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function setoresIdiomas()
    {
        return $this->hasMany(SetorIdioma::class);
    }
}