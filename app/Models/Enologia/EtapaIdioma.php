<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaIdioma extends Model
{
    protected $connection = 'enologia';
    protected $table = 'etapas_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function etapa()
    {
        return $this->belongsTo(Etapa::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}
