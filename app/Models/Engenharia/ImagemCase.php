<?php

namespace App\Models\Engenharia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemCase extends Model
{
    protected $connection = 'engenharia';
    protected $table = 'imagens_cases';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function caseCliente()
    {
        return $this->belongsTo(CaseCliente::class);
    }
}