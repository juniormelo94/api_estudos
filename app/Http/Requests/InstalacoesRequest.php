<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class InstalacoesRequest extends FormRequest
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
            'endereco' => ['required', 'string', 'max:200'],
            'bairro' => ['required', 'string', 'max:100'],
            'numero' => ['required', 'string', 'max:20'],
            'complemento' => ['required', 'string', 'max:100'],
            'cep' => ['required', 'string', 'max:10'],
            'cidade' => ['required', 'string', 'max:100'],
            'uf' => ['required', 'string', 'max:2'],
            'email' => ['nullable', 'email', 'string', 'max:100'],
            'telefone' => ['nullable', 'string', 'max:18'],
            'celular' => ['nullable', 'string', 'max:18'],
            'whatsapp' => ['nullable', 'string', 'max:18'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:100'],
            'divisoes_id' => ['required', 'integer', Rule::exists('divisoes', 'id')],
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
            'endereco.required' => 'O endereço é obrigatório.',
            'endereco.string' => 'O endereço precisa ser no formato string.',
            'endereco.max' => 'O endereço deve conter no máximo 200 caracteres.',
            'bairro.required' => 'O bairro é obrigatório.',
            'bairro.string' => 'O bairro precisa ser no formato string.',
            'bairro.max' => 'O bairro deve conter no máximo 100 caracteres.',
            'numero.required' => 'O número é obrigatório.',
            'numero.string' => 'O número precisa ser no formato string.',
            'numero.max' => 'O número deve conter no máximo 20 caracteres.',
            'complemento.required' => 'O complemento é obrigatório.',
            'complemento.string' => 'O complemento precisa ser no formato string.',
            'complemento.max' => 'O complemento deve conter no máximo 100 caracteres.',
            'cep.required' => 'O cep é obrigatório.',
            'cep.string' => 'O cep precisa ser no formato string.',
            'cep.max' => 'O cep deve conter no máximo 10 caracteres.',
            'cidade.required' => 'A cidade é obrigatória.',
            'cidade.string' => 'A cidade precisa ser no formato string.',
            'cidade.max' => 'A cidade deve conter no máximo 10 caracteres.',
            'uf.required' => 'A UF é obrigatória.',
            'uf.string' => 'A UF precisa ser no formato string.',
            'uf.max' => 'A UF deve conter no máximo 10 caracteres.',
            'email.email' => 'O e-mail precisa ser no formato email.',
            'email.string' => 'O e-mail precisa ser no formato string.',
            'email.max' => 'O e-mail deve conter no máximo 100 caracteres.', 
            'telefone.string' => 'O telefone precisa ser no formato string.',
            'telefone.max' => 'O telefone deve conter no máximo 18 caracteres.',
            'celular.string' => 'O celular precisa ser no formato string.',
            'celular.max' => 'O celular deve conter no máximo 18 caracteres.',
            'whatsapp.string' => 'O WhatsApp precisa ser no formato string.',
            'whatsapp.max' => 'O WhatsApp deve conter no máximo 18 caracteres.',
            'instagram.string' => 'O Instagram precisa ser no formato string.',
            'instagram.max' => 'O Instagram deve conter no máximo 100 caracteres.',
            'facebook.string' => 'O Facebook precisa ser no formato string.',
            'facebook.max' => 'O Facebook deve conter no máximo 100 caracteres.',
            'divisoes_id.required' => 'O id da divisão é obrigatório.',
            'divisoes_id.exists' => 'A divisão com id :input não existe.',
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
