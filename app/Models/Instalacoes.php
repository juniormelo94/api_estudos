<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instalacoes extends Model
{
    use HasFactory;

        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instalacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'endereco',
        'bairro',
        'numero',
        'complemento',
        'cep',
        'cidade',
        'uf',
        'email',
        'telefone',
        'celular',
        'whatsapp',
        'instagram',
        'facebook',
        'divisoes_id',
        'criado_por',
        'atualizado_por',
        'status',
    ];
}
