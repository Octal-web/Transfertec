<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloco extends Model
{
    protected $connection = 'engenharia';
    protected $table = 'blocos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function blocosIdiomas()
    {
        return $this->hasMany(BlocoIdioma::class);
    }

    public function caseCliente()
    {
        return $this->belongsTo(CaseCliente::class, 'case_id');
    }
}