<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisoes extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'divisoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'ramo',
        'cnpj',
        'cor_primaria',
        'cor_secundaria',
        'cor_tercearia',
        'logo_img',
        'criado_por',
        'atualizado_por',
        'status',
    ];
}
