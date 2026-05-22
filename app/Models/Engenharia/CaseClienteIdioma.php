<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseClienteIdioma extends Model
{
    protected $connection = 'engenharia';
    protected $table = 'cases_clientes_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function caseCliente()
    {
        return $this->belongsTo(CaseCliente::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}
