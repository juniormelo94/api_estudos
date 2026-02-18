<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alternativas;

class Questoes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'texto',
        'img',
        'disciplinas_id',
        'status',
        'criado_por',
        'atualizado_por',
    ];

    /**
     * Get the record associated with the Alternativas.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Alternativas::class
     */
    public function alternativas()
    {
        return $this->hasMany(Alternativas::class, 'questoes_id');
    }
}
