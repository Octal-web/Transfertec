<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemProcesso extends Model
{
    protected $connection = 'enologia';
    protected $table = 'imagens_processos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }
}