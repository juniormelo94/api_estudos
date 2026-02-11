<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class ProdutosRequest extends FormRequest
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
        return [
            'nome' => ['required', 'string', 'max:200'],
            'tipo' => ['required', 'string'],
            'descricao' => ['required', 'string', 'max:200'],
            'codigo_barras' => ['nullable', 'string'],
            'qr_code' => ['nullable', 'string'],
            'img_1' => ['nullable', 'string'],
            'img_2' => ['nullable', 'string'],
            'img_3' => ['nullable', 'string'],
            'marcas_id' => ['required', 'integer', Rule::exists('marcas', 'id')],
            'criado_por' => ['nullable', 'string'],
            'atualizado_por' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.string' => 'O nome precisa ser no formato string.',
            'nome.max' => 'O nome deve conter no máximo 200 caracteres.',
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.string' => 'O tipo precisa ser no formato string.',
            'descricao.required' => 'A descricão é obrigatório.',
            'descricao.string' => 'A descricão precisa ser no formato string.',
            'descricao.max' => 'A descricão deve conter no máximo 200 caracteres.',
            'codigo_barras.string' => 'O código barras precisa ser no formato string.',
            'qr_code.string' => 'O QR Code precisa ser no formato string.',
            'img_1.string' => 'A imagem 1 precisa ser no formato string.',
            'img_2.string' => 'A imagem 2 precisa ser no formato string.',
            'img_3.string' => 'A imagem 3 precisa ser no formato string.',
            'marcas_id.required' => 'O id da marca é obrigatório.',
            'marcas_id.exists' => 'A marca com id :input não existe.',
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
