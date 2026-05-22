<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $connection = 'engenharia';
    protected $table = 'posts';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function postsIdiomas()
    {
        return $this->hasMany(PostIdioma::class);
    }

    public function categoria()
    {
        return $this->belongsTo(PostCategoria::class, 'post_categoria_id');
    }
}