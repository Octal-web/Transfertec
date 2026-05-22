<?php

namespace App\Http\Requests\Engenharia;

use Illuminate\Foundation\Http\FormRequest;

class PostBudgetRequest extends FormRequest
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
    public function rules()
    {
        return [
            // 'expectativa_compra' => 'required|in:nesta_semana,neste_mes,proximo_mes,proximo_semestre,proximo_ano',
            'nome' => 'required|string|max:60',
            'email' => 'required|email|max:60',
            'telefone' => 'required|celular_com_ddd',
            'cnpj' => 'required|cnpj',
            'cargo' => 'required|string|max:60',
            'forma_contato' => 'required|in:email,telefone,whatsapp',
            'mensagem' => 'required|string',
            'politica' => 'required|accepted',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            // 'expectativa_compra.required' => 'Por favor, selecione uma expectativa de compra.',
            // 'expectativa_compra.in' => 'A expectativa de compra selecionada é inválida.',
            'nome.required' => 'Por favor, insira seu nome.',
            'nome.max' => 'O nome não pode ter mais que 60 caracteres.',
            'email.required' => 'Por favor, insira seu e-mail.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais que 60 caracteres.',
            'telefone.required' => 'Por favor, insira seu telefone.',
            'telefone.celular_com_ddd' => 'Por favor, informe um telefone válido.',
            'cnpj.required' => 'Por favor, insira seu CNPJ.',
            'cnpj.cnpj' => 'Por favor, insira um CNPJ válido.',
            'cargo.required' => 'Por favor, insira seu cargo.',
            'cargo.max' => 'O cargo não pode ter mais que 60 caracteres.',
            'forma_contato.required' => 'Por favor, selecione como deseja ser contatado.',
            'forma_contato.in' => 'A forma de contato selecionada é inválida.',
            'mensagem.required' => 'Por favor, informe a sua mensagem.',
            'politica.required' => 'Para continuar, você deve concordar com os termos.',
            'politica.accepted' => 'Para continuar, você deve concordar com os termos.',
        ];
    }
}