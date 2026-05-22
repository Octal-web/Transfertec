<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoIdioma extends Model {
    protected $connection = 'engenharia';
    protected $table = 'produtos_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}