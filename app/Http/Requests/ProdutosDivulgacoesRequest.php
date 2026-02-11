<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\ProdutosDivulgacoes;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class ProdutosDivulgacoesRequest extends FormRequest
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
            'img_padrao_1' => ['nullable', 'string'],
            'img_padrao_2' => ['nullable', 'string'],
            'img_padrao_3' => ['nullable', 'string'],
            'img_promocao_1' => ['nullable', 'string'],
            'img_promocao_2' => ['nullable', 'string'],
            'img_promocao_3' => ['nullable', 'string'],
            'produtos_id' => [
                'required', 
                'integer', 
                Rule::exists('produtos', 'id'),
                function ($attribute, $value, $fail) {
                    if ($this->id == null && ProdutosDivulgacoes::where('produtos_id', $value)->exists()) {
                        $fail('O produto já possui um amazenamento de divulgação.');
                    }
                },
            ],
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
            'img_padrao_1.string' => 'A imagem padrão 1 precisa ser no formato string.',
            'img_padrao_2.string' => 'A imagem padrão 2 precisa ser no formato string.',
            'img_padrao_3.string' => 'A imagem padrão 3 precisa ser no formato string.',
            'img_promocao_1.string' => 'A imagem promoção 1 precisa ser no formato string.',
            'img_promocao_2.string' => 'A imagem promoção 2 precisa ser no formato string.',
            'img_promocao_3.string' => 'A imagem promoção 3 precisa ser no formato string.',
            'produtos_id.required' => 'O id da produto é obrigatório.',
            'produtos_id.exists' => 'O produto com id :input não existe.',
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
