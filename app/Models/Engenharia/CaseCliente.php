<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseCliente extends Model
{
    protected $connection = 'engenharia';
    protected $table = 'cases_clientes';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function casesClientesIdiomas()
    {
        return $this->hasMany(CaseClienteIdioma::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function blocos()
    {
        return $this->hasMany(Bloco::class, 'case_id');
    }

    public function imagens()
    {
        return $this->hasMany(ImagemCase::class, 'case_id');
    }
}