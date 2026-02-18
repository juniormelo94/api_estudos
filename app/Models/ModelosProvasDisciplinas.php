<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disciplinas;

class ModelosProvasDisciplinas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'modelos_provas_disciplinas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'modelos_provas_id',
        'disciplinas_id',
        'qtd_questoes',
        'status',
        'criado_por',
        'atualizado_por',
    ];

    /**
     * Get the record associated with the Disciplinas.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Disciplinas::class
     */
    public function disciplina()
    {
        return $this->hasOne(Disciplinas::class, 'id', 'disciplinas_id');
    }
}
