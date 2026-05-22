<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model {
    protected $connection = 'enologia';
    protected $table = 'conteudos_parametros';
    
    public $timestamps = false;

    public function conteudos()
    {
        return $this->belongsTo(Conteudo::class, 'conteudo_id');
    }
}