<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combos extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'combos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'tipo',
        'descricao',
        'codigo_barras',
        'qr_code',
        'img_1',
        'img_2',
        'img_3',
        'instalacoes_id',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    /**
     * Get the record associated with the CombosProdutos.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return CombosProdutos::class
     */
    public function combo_produtos()
    {
        return $this->hasMany(CombosProdutos::class, 'combos_id');
    }
}
