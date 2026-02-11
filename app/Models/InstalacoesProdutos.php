<?php

namespace App\Models;

use App\Models\Estoques;
use App\Models\Produtos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstalacoesProdutos extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instalacoes_produtos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'observacoes',
        'instalacoes_id',
        'produtos_id',
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
     * Get the record associated with the Estoques.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Estoques::class
     */
    public function estoque()
    {
        return $this->hasMany(Estoques::class, 'produtos_id', 'produtos_id');
    }
}
