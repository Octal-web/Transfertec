<?php

namespace App\Http\Requests\Engenharia\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostCaseRequest extends FormRequest
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
            'nome'  => 'required',
            'empresa'  => 'nullable',
            'descricao'  => 'required|max:220',
            'produto_id'  => 'required|integer',
            'img' => inertia()->getShared('action') == 'novo' ? 'required|image|mimes:png,jpg|max:2048' : 'nullable|image|mimes:png,jpg|max:2048',
            'titulo_pagina'  => 'required|max:150',
            'descricao_pagina'  => 'required|max:220',
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
            'nome.required'  => 'Por favor, informe o nome.',
            'empresa.required'  => 'Por favor, informe a empresa.',
            'descricao.required'  => 'Por favor, informe a descrição.',
            'descricao.max'  => 'A descrição não pode exceder 220 caracteres.',
            'produto_id.required'  => 'Por favor, informe o produto.',
            'img.required' => 'Por favor, selecione uma imagem.',
            'img.image' => 'Por favor, selecione uma imagem válida.',
            'img.mimes' => 'Os formatos de imagem válidos são: JPG e PNG.',
            'img.max' => 'Por favor, envie um arquivo menor que 2MB.',
            'titulo_pagina.required'  => 'Por favor, informe o título da página.',
            'titulo_pagina.max'  => 'O título da página não pode exceder 150 caracteres.',
            'descricao_pagina.required'  => 'Por favor, informe a descrição da página.',
            'descricao_pagina.max'  => 'A descrição da página não pode exceder 220 caracteres.',
        ];
    }
}