<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Model;

class Idioma extends Model {
    protected $connection = 'enologia';
    protected $table = 'idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';
}