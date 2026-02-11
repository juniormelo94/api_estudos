<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marcas;
use App\Models\InstalacoesProdutos;

class Produtos extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produtos';

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
        return $this->hasOne(Marcas::class, 'id', 'marcas_id');
    }

    /**
     * Get the record associated with the InstalacoesProdutos.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return InstalacoesProdutos::class
     */
    public function instalacao_produtos()
    {
        return $this->hasMany(InstalacoesProdutos::class, 'produtos_id');
    }

    /**
     * Get the record associated with the ProdutosDivulgacoes.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return ProdutosDivulgacoes::class
     */
    public function produto_divulgacao()
    {
        return $this->hasMany(ProdutosDivulgacoes::class, 'produtos_id');
    }
}
