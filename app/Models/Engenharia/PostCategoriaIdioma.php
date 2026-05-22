<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategoriaIdioma extends Model {
    protected $connection = 'engenharia';
    protected $table = 'posts_categorias_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function postCategoria()
    {
        return $this->belongsTo(PostCategoria::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}