<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processo extends Model
{
    protected $connection = 'enologia';
    protected $table = 'processos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function processosIdiomas()
    {
        return $this->hasMany(ProcessoIdioma::class);
    }

    public function imagens()
    {
        return $this->hasMany(ImagemProcesso::class);
    }
}