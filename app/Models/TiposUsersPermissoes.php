<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permissoes;

class TiposUsersPermissoes extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tipos_users_permissoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipos_users_id',
        'permissoes_id',
    ];

    /**
     * Get the record associated with the Permissoes.
     *
     * @version 1.0.0
     * @author Junior Melo
     * @author 
     *
     * @return Permissoes::class
     */
    public function permissao()
    {
        return $this->belongsTo(Permissoes::class, 'permissoes_id', 'id');
    }
}
