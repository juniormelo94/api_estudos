<?php

namespace App\Http\Requests;

use App\Models\ColaboradoresUsers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $colaboradorUser = ColaboradoresUsers::where('users_id', $this->id)->first();

        $rules = [
            // Regras relacionadas a tabela Users
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email')->ignore($this->id)],
            'password' => ['string', 'min:6'],
            // Regras relacionadas a tabela ColaboradoresUsers
            'cargo' => ['required', 'string', 'max:100'],
            'divisoes_ids' => ['nullable', 'array'],
            'divisoes_ids.*' => ['required', 'integer', Rule::exists('divisoes', 'id')],
            'instalacoes_ids' => ['nullable', 'array'],
            'instalacoes_ids.*' => ['required', 'integer', Rule::exists('instalacoes', 'id')],
            'colaboradores_id' => [
                'integer', 
                Rule::unique('colaboradores_users', 'colaboradores_id')->ignore(optional($colaboradorUser)->id), 
                Rule::exists('colaboradores', 'id')
            ],
            'tipos_users_id' => [
                'integer', 
                Rule::exists('tipos_users', 'id')
            ],
            'status' => ['required', 'string'],
        ];

        if($this->method() == 'POST')
        {
            $rules = [
                // Regras relacionadas a tabela Users
                'name' => ['required', 'string', 'max:50'],
                'email' => ['required', 'string', 'email', Rule::unique('users', 'email')->ignore($this->id)],
                'password' => ['required', 'string', 'min:6', 'same:password_confirmation'],
                'password_confirmation' => ['required'],
                // Regras relacionadas a tabela ColaboradoresUsers
                'cargo' => ['required', 'string', 'max:100'],
                'divisoes_ids' => ['nullable', 'array'],
                'divisoes_ids.*' => ['required', 'integer', Rule::exists('divisoes', 'id')],
                'instalacoes_ids' => ['nullable', 'array'],
                'instalacoes_ids.*' => ['required', 'integer', Rule::exists('instalacoes', 'id')],
                'colaboradores_id' => [
                    'required', 
                    'integer', 
                    Rule::unique('colaboradores_users', 'colaboradores_id')->ignore(optional($colaboradorUser)->id), 
                    Rule::exists('colaboradores', 'id')
                ],
                'tipos_users_id' => [
                    'integer', 
                    Rule::exists('tipos_users', 'id')
                ],
                'status' => ['required', 'string'],
            ];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome precisa ser no formato string.',
            'name.max' => 'O nome deve conter no máximo 50 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.string' => 'O e-mail precisa ser no formato string.',
            'email.email' => 'O e-mail precisa ser no formato email.',
            'email.unique' => 'Já existe um cadastro com esse e-mail.',
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha precisa ser no formato string.',
            'password.min' => 'A senha deve conter no mínimo 6 caracteres.',
            'password.same' => 'A senha e a confirmação de senha estão diferentes.',
            'password_confirmation.required' => 'A confirmação de senha é obrigatória.',
            'cargo.required' => 'O cargo é obrigatório.',
            'cargo.string' => 'O cargo precisa ser no formato string.',
            'cargo.max' => 'O cargo deve conter no máximo 100 caracteres.',
            'divisoes_ids.array' => 'Os ids das divisões devem ser um array.',
            'divisoes_ids.*.exists' => 'A divisão com id :input não existe.',
            'instalacoes_ids.array' => 'Os ids das instalações devem ser um array.',
            'instalacoes_ids.*.exists' => 'A instalação com id :input não existe.',
            'colaboradores_id.required' => 'O id do colaborador é obrigatório.',
            'colaboradores_id.unique' => 'Já existe um usuário para esse colaborador.',
            'colaboradores_id.exists' => 'O colaborador com id :input não existe.',
            'tipos_users_id.required' => 'O id do tipo do usuário é obrigatório.',
            'tipos_users_id.exists' => 'O tipo do usuário com id :input não existe.',
            'status.required' => 'O status é obrigatório.',
            'status.string' => 'O status precisa ser no formato string.',
        ];
    }

    /**
     * Overriding the event validator for custom error response.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                ["status" => false, "erros" => $validator->errors()], 
                422,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            )
        );
    }
}