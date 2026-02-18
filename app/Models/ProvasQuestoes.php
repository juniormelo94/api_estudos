<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Questoes;

class ProvasQuestoes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provas_questoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'provas_id',
        'questoes_id',
    ];

    /**
     * Get the record associated with the Questoes.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Questoes::class
     */
    public function questao()
    {
        return $this->belongsTo(Questoes::class, 'questoes_id', 'id');
    }
}
