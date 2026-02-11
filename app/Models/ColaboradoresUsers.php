<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Colaboradores;
use App\Models\TiposUsers;

class ColaboradoresUsers extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'colaboradores_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cargo',
        'divisoes_ids',
        'instalacoes_ids',
        'colaboradores_id',
        'users_id',
        'tipos_users_id',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    protected  $casts = [ 
        'divisoes_ids' => 'array',
        'instalacoes_ids' => 'array',
    ];

    /**
     * Get the record associated with the TiposUsers.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return TiposUsers::class
     */
    public function tipo_user()
    {
        return $this->hasOne(TiposUsers::class,'id', 'tipos_users_id');
    }

    /**
     * Get the record associated with the Colaboradores.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Colaboradores::class
     */
    public function colaborador()
    {
        return $this->hasOne(Colaboradores::class,'id', 'colaboradores_id');
    }
}
