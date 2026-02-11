<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TiposUsersPermissoes;

class TiposUsers extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tipos_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo',
        'descricao',
        'criado_por',
        'atualizado_por',
        'status',
    ];

    /**
     * Get the record associated with the TiposUsersPermissoes.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return TiposUsersPermissoes::class
     */
    public function tipos_users_permissoes()
    {
        return $this->hasMany(TiposUsersPermissoes::class, 'tipos_users_id');
    }
}
