<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $connection = 'engenharia';
    protected $table = 'etapas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function etapasIdiomas()
    {
        return $this->hasMany(EtapaIdioma::class);
    }
}