<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acontecimento extends Model {
    protected $connection = 'enologia';
    protected $table = 'acontecimentos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function acontecimentosIdiomas()
    {
        return $this->hasMany(AcontecimentoIdioma::class);
    }
}