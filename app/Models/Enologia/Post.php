<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $connection = 'enologia';
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