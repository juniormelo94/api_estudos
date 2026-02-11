<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InstalacoesColaboradores;

class Colaboradores extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'colaboradores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome_completo',
        'primeiro_nome',
        'ultimo_nome',
        'apelido',
        'cpf',
        'data_nascimento',
        'rg',
        'sexo',
        'estado_civil',
        'img',
        'email_pessoal',
        'telefone_pessoal',
        'celular_pessoal',
        'whatsapp_pessoal',
        'instagram_pessoal',
        'facebook_pessoal',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    /**
     * Get the record associated with the InstalacoesColaboradores.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return InstalacoesColaboradores::class
     */
    public function instalacao_colaboradores()
    {
        return $this->hasMany(InstalacoesColaboradores::class, 'colaboradores_id');
    }
}
