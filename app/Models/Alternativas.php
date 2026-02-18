<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternativas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alternativas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'texto',
        'img',
        'resposta_correta',
        'questoes_id',
        'status',
        'criado_por',
        'atualizado_por',
    ];
}
