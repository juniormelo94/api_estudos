<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosDivulgacoes extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produtos_divulgacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'img_padrao_1',
        'img_padrao_2',
        'img_padrao_3',
        'img_promocao_1',
        'img_promocao_2',
        'img_promocao_3',
        'produtos_id',
        'criado_por',
        'atualizado_por',
        'status',
    ];
}
