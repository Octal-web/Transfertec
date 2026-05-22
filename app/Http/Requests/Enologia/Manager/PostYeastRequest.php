<?php

namespace App\Http\Requests\Enologia\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostYeastRequest extends FormRequest
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
            'nome'      => 'required',
            'marca_id'  => 'required|integer',
            'utilidade' => 'required',
            'descricao' => 'required',
            'detalhes'  => 'required',
            'img'       => inertia()->getShared('action') == 'novo' ? 'required|image|mimes:png,jpg|max:5120' : 'nullable|image|mimes:png,jpg|max:5120',
            'arq_tec'   => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:51200',
            'arq_seg'   => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:51200',
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
            'nome.required'       => 'Por favor, informe o nome.',
            'marca_id.required'   => 'Por favor, informe a marca.',
            'utilidade.required'  => 'Por favor, informe a utilidade.',
            'descricao.required'  => 'Por favor, informe a descrição.',
            'detalhes.required'   => 'Por favor, informe os detalhes.',

            'img.required'        => 'Por favor, selecione uma imagem.',
            'img.image'           => 'Por favor, selecione uma imagem válida.',
            'img.mimes'       => 'Os formatos de imagem válidos são: JPG e PNG.',
            'img.max'             => 'Por favor, envie um arquivo menor que 5MB.',

            'arq_tec.required'    => 'Por favor, selecione uma ficha técnica.',
            'arq_tec.mimes'       => 'Os formatos de arquivo válidos são: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.',
            'arq_tec.max'         => 'Por favor, envie um arquivo menor que 50MB.',

            'arq_seg.required'    => 'Por favor, selecione uma ficha de segurança.',
            'arq_seg.mimes'       => 'Os formatos de arquivo válidos são: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.',
            'arq_seg.max'         => 'Por favor, envie um arquivo menor que 50MB.',
        ];
    }
}