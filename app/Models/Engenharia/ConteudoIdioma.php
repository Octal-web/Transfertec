<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConteudoIdioma extends Model {
    protected $connection = 'engenharia';
    protected $table = 'conteudos_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function conteudo()
    {
        return $this->belongsTo(Conteudo::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}