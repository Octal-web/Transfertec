<?php

namespace App\Models\Enologia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    protected $connection = 'enologia';
    protected $table = 'parceiros';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];
}
