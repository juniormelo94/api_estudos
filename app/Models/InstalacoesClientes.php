<?php

namespace App\Models;

use App\Models\Clientes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstalacoesClientes extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instalacoes_clientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'observacoes',
        'instalacoes_id',
        'clientes_id',
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
        return $this->belongsTo(Clientes::class, 'clientes_id', 'id');
    }
}
