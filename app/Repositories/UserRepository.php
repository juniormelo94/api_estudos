<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\ColaboradoresUsers;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\User $model
     * @return void
     */
    public function __construct(protected User $model)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function getAll()
    {
        $request = request();

        $query = $this->model->query();

        if ($request->has('criado_por')) {
            $query->where('criado_por', $request->criado_por);
        }

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('created_at', '>=', $request->criado_de)
                  ->whereDate('created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->whereHas('colaborador_user', function ($subQuery) use ($request) {
                $subQuery->where('status', $request->status);
            });
        }

        if ($request->has('colaboradores_id')) {
            $query->whereHas('colaborador_user', function ($subQuery) use ($request) {
                $subQuery->where('colaboradores_id', $request->colaboradores_id);
            });
        }

        if ($request->has('pesquisar')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%$request->pesquisar%")
                ->orWhere('email', 'like', "%$request->pesquisar%")
                ->orWhereHas('colaborador_user', function ($subQuery) use ($request) {
                    $subQuery->where('tipo_user', 'like', "%$request->pesquisar%");
                });
            });
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('name', 'asc')
                         ->with('colaborador_user')->paginate($request->por_pagina);
        }

        return $query->orderBy('name', 'asc')
                     ->with('colaborador_user')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function create($request)
    {
        $user = $this->model;

        return tap($user, function ($user) use ($request) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function createUserColaborador($request)
    {
        $user = $this->model;

        return tap(
            $user->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]), 
            function ($user) use ($request) {
                $user->colaborador_user()->create([
                    'cargo' => $request->cargo,
                    'divisoes_ids' => $request->divisoes_ids,
                    'instalacoes_ids' => $request->instalacoes_ids,
                    'colaboradores_id' => $request->colaboradores_id,
                    'users_id' => $user->id,
                    'tipos_users_id' => $request->tipos_users_id,
                    'criado_por' => $request->user()->colaborador_user?->colaborador->cpf,
                    'status' => $request->status,
                ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        return $this->model->with('colaborador_user.tipo_user.tipos_users_permissoes.permissao')
                           ->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     */
    public function update($request, $id)
    {
        $user = $this->model->findOrFail($id);

        return tap($user, function ($user) use ($request) {
            if($request->has('password'))
            {
                $request->merge([
                    'password' => Hash::make($request->password)
                ]);
            }

            $user->update($request->all());
        });
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function updateUserColaborador($request, $id)
    {
        $user = $this->model->with('colaborador_user')->findOrFail($id);

        return tap($user, function ($user) use ($request) {
                $request->merge([
                    'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf
                ]);

                if($request->has('password'))
                {
                    $request->merge([
                        'password' => Hash::make($request->password)
                    ]);
                }

                $user->update($request->all());

                $user->colaborador_user()->update([
                    'cargo' => $request->cargo,
                    'divisoes_ids' => $request->divisoes_ids,
                    'instalacoes_ids' => $request->instalacoes_ids,
                    'tipos_users_id' => $request->tipos_users_id,
                    'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
                    'status' => $request->status
                ]);

                $user->load('colaborador_user');
            });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $user = $this->model->findOrFail($id);

        return $user->delete();
    }
}