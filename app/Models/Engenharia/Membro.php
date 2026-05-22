<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membro extends Model {
    protected $connection = 'engenharia';
    protected $table = 'membros';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function membrosIdiomas()
    {
        return $this->hasMany(MembroIdioma::class);
    }
}