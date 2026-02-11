<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\InstalacoesMarcas;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class InstalacoesMarcasRequest extends FormRequest
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
            'observacoes' => ['nullable', 'string'],
            'instalacoes_id' => ['required', 'integer', Rule::exists('instalacoes', 'id')],
            'marcas_id' => [
                'required', 
                'integer', 
                Rule::exists('marcas', 'id'),
                function ($attribute, $value, $fail) {
                    if ($this->id == null && InstalacoesMarcas::where('instalacoes_id', $this->instalacoes_id)
                        ->where('marcas_id', $value)
                        ->exists()) {
                        $fail('A marca já está associada a essa instalação.');
                    }
                },
            ],
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
            'observacoes.string' => 'A observacão precisa ser no formato string.',
            'instalacoes_id.required' => 'O id da instalação é obrigatório.',
            'instalacoes_id.exists' => 'A instalação com id :input não existe.',
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
