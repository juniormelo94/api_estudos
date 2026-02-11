<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CombosProdutos extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'combos_produtos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'combos_id',
        'produtos_id',
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
}
