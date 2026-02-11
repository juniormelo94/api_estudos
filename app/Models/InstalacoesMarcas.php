<?php

namespace App\Models;

use App\Models\Marcas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstalacoesMarcas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instalacoes_marcas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'observacoes',
        'instalacoes_id',
        'marcas_id',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    /**
     * Get the record associated with the Marcas.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Marcas::class
     */
    public function marca()
    {
        return $this->belongsTo(Marcas::class, 'marcas_id', 'id');
    }
}
