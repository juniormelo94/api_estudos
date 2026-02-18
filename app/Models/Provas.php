<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProvasQuestoes;


class Provas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'modelos_provas_id',
        'status',
        'criado_por',
        'atualizado_por',
    ];

    /**
     * Get the record associated with the ProvasQuestoes.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return ProvasQuestoes::class
     */
    public function provas_questoes()
    {
        return $this->hasMany(ProvasQuestoes::class, 'provas_id');
    }
}
