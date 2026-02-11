<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estoques;
use App\Models\Vendas;

class VendasEstoques extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vendas_estoques';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vendas_id',
        'estoques_id',
        'status',
    ];

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
        return $this->belongsTo(Estoques::class, 'estoques_id', 'id');
    }

    /**
     * Get the record associated with the Vendas.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Vendas::class
     */
    public function venda()
    {
        return $this->belongsTo(Vendas::class, 'vendas_id', 'id');
    }
}
