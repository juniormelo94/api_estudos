<?php

namespace App\Models;

use App\Models\Produtos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VendasEstoques;

class Estoques extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estoques';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'desconto_compra',
        'preco_compra_original',
        'preco_compra_desconto',
        'desconto_venda',
        'preco_venda_original',
        'preco_venda_desconto',
        'preco_venda_avista',
        'vendido',
        'desconto_pagamento_avista',
        'vencimento',
        'produtos_id',
        'instalacoes_id',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    /**
     * Get the record associated with the Produtos.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Produtos::class
     */
    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produtos_id', 'id');
    }

    /**
     * Get the record associated with the VendasEstoques.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return VendasEstoques::class
     */
    public function venda_estoque()
    {
        return $this->hasOne(VendasEstoques::class, 'estoques_id');
    }
}
