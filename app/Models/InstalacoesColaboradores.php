<?php

namespace App\Models;

use App\Models\Colaboradores;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstalacoesColaboradores extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instalacoes_colaboradores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'observacoes',
        'instalacoes_id',
        'colaboradores_id',
        'criado_por',
        'atualizado_por',
        'status',
    ];

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
        return $this->belongsTo(Colaboradores::class, 'colaboradores_id', 'id');
    }
}
