<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clientes;
use App\Models\Colaboradores;
use App\Models\FormasPagamentos;
use App\Models\VendasEstoques;

class Vendas extends Model
{
    use HasFactory;

        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vendas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'preco_total',
        'lucro_total_original',
        'lucro_total_desconto',
        'maquina_cartao',
        'quantidade_parcelas',
        'valor_pacelas',
        'taxa_juros',
        'formas_pagamentos_id',
        'clientes_id',
        'colaboradores_id',
        'instalacoes_id,',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    /**
     * Get the record associated with the Clientes.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Clientes::class
     */
    public function cliente()
    {
        return $this->hasOne(Clientes::class,'id', 'clientes_id');
    }

    /**
     * Get the record associated with the Colaboradores.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Colaboradores::class
     */
    public function colaborador()
    {
        return $this->hasOne(Colaboradores::class, 'id', 'colaboradores_id');
    }

    /**
     * Get the record associated with the FormasPagamentos.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return FormasPagamentos::class
     */
    public function forma_pagamento()
    {
        return $this->hasOne(FormasPagamentos::class, 'id', 'formas_pagamentos_id');
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
    public function vendas_estoques()
    {
        return $this->hasMany(VendasEstoques::class, 'vendas_id');
    }
}
