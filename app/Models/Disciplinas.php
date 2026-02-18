<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplinas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'disciplinas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'abreviacao',
        'status',
        'criado_por',
        'atualizado_por',
    ];
}
