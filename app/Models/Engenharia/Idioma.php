<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Model;

class Idioma extends Model {
    protected $connection = 'engenharia';
    protected $table = 'idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';
}