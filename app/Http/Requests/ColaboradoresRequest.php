<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class ColaboradoresRequest extends FormRequest
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
        $rules = [
            'nome_completo' => ['required', 'string', 'max:200'],
            'primeiro_nome' => ['required', 'string', 'max:50'],
            'ultimo_nome' => ['required', 'string', 'max:50'],
            'apelido' => ['nullable', 'string', 'max:50'],
            'cpf' => ['required', 'string', 'max:11', Rule::unique('colaboradores', 'cpf')->ignore($this->id)],
            'data_nascimento' => ['required', 'date', 'max:25'],
            'rg' => ['nullable', 'string', 'max:7'],
            'sexo' => ['required', 'string', 'max:50'],
            'estado_civil' => ['nullable', 'string', 'max:50'],
            'img' => ['nullable', 'string'],
            'email_pessoal' => ['nullable', 'email', 'string', 'max:100'],
            'telefone_pessoal' => ['nullable', 'string', 'max:18'],
            'celular_pessoal' => ['nullable', 'string', 'max:18'],
            'whatsapp_pessoal' => ['nullable', 'string', 'max:18'],
            'instagram_pessoal' => ['nullable', 'string', 'max:100'],
            'facebook_pessoal' => ['nullable', 'string', 'max:100'],
            'criado_por' => ['nullable', 'string'],
            'atualizado_por' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ];

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
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'nome_completo.string' => 'O nome completo precisa ser no formato string.',
            'nome_completo.max' => 'O nome completo deve conter no máximo 200 caracteres.',
            'primeiro_nome.required' => 'O primeiro nome é obrigatório.',
            'primeiro_nome.string' => 'O primeiro nome precisa ser no formato string.',
            'primeiro_nome.max' => 'O primeiro nome deve conter no máximo 50 caracteres.',
            'ultimo_nome.required' => 'O último nome é obrigatório.',
            'ultimo_nome.string' => 'O último nome precisa ser no formato string.',
            'ultimo_nome.max' => 'O último nome deve conter no máximo 50 caracteres.',
            'apelido.string' => 'O apelido precisa ser no formato string.',
            'apelido.max' => 'O apelido deve conter no máximo 50 caracteres.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.string' => 'O CPF precisa ser no formato string.',
            'cpf.max' => 'O CPF deve conter no máximo 11 caracteres.',
            'cpf.unique' => 'Já existe um cadastro com esse CPF.',
            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.date' => 'A data de nascimento precisa ser no formato data.',
            'data_nascimento.max' => 'A data de nascimento deve conter no máximo 10 caracteres.',
            'rg.string' => 'O RG precisa ser no formato string.',
            'rg.max' => 'O RG deve conter no máximo 7 caracteres.',
            'sexo.required' => 'O sexo é obrigatório.',
            'sexo.string' => 'O sexo precisa ser no formato string.',
            'sexo.max' => 'O sexo deve conter no máximo 50 caracteres.',
            'estado_civil.string' => 'O estado civil precisa ser no formato string.',
            'estado_civil.max' => 'O estado civil deve conter no máximo 50 caracteres.',
            'img.string' => 'A imagem precisa ser no formato string.',
            'email_pessoal.email' => 'O e-mail pessoal precisa ser no formato email.',
            'email_pessoal.string' => 'O e-mail pessoal precisa ser no formato string.',
            'email_pessoal.max' => 'O e-mail pessoal deve conter no máximo 100 caracteres.', 
            'telefone_pessoal.string' => 'O telefone pessoal precisa ser no formato string.',
            'telefone_pessoal.max' => 'O telefone pessoal deve conter no máximo 18 caracteres.',
            'celular_pessoal.string' => 'O celular pessoal precisa ser no formato string.',
            'celular_pessoal.max' => 'O celular pessoal deve conter no máximo 18 caracteres.',
            'whatsapp_pessoal.string' => 'O WhatsApp pessoal precisa ser no formato string.',
            'whatsapp_pessoal.max' => 'O WhatsApp pessoal deve conter no máximo 18 caracteres.',
            'instagram_pessoal.string' => 'O Instagram pessoal precisa ser no formato string.',
            'instagram_pessoal.max' => 'O Instagram pessoal deve conter no máximo 100 caracteres.',
            'facebook_pessoal.string' => 'O Facebook pessoal precisa ser no formato string.',
            'facebook_pessoal.max' => 'O Facebook pessoal deve conter no máximo 100 caracteres.',
            'criado_por.string' => 'O criado por precisa ser no formato string.',
            'atualizado_por.string' => 'O atualizado por precisa ser no formato string.',
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
