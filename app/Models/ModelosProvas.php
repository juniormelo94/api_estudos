<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ModelosProvasDisciplinas;

class ModelosProvas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'modelos_provas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'criado_por',
        'atualizado_por',
    ];

    /**
     * Get the record associated with the ModelosProvasDisciplinas.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return ModelosProvasDisciplinas::class
     */
    public function modelos_provas_disciplinas()
    {
        return $this->hasMany(ModelosProvasDisciplinas::class, 'modelos_provas_id');
    }
}
