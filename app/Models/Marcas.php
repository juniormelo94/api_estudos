<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InstalacoesMarcas;

class Marcas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'marcas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'ramo',
        'cnpj',
        'logo_img',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    /**
     * Get the record associated with the InstalacoesMarcas.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return InstalacoesMarcas::class
     */
    public function instalacao_marcas()
    {
        return $this->hasMany(InstalacoesMarcas::class, 'marcas_id');
    }
}
