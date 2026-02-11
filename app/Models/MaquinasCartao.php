<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaquinasCartao extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maquinas_cartaos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'modelo',
        'empresa_responsavel',
        'bandeiras_aceitas',
        'taxa_debito',
        'taxas_parcelas',
        'taxas_links_parcelas',
        'taxa_pix',
        'instalacoes_id',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    protected  $casts = [ 
        'bandeiras_aceitas' => 'array',
    ];
}
