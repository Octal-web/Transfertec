import React from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHdd, faSave, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Enologia/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, idiomas, equipamento, equipamentosSubcategorias } = usePage().props;
    console.log(equipamento)
    const { data, setData, post, processing, errors } = useForm(equipamento);

    const breadcrumbItems = [
        { label: 'Home', link: 'Enologia.Manager.Equipamentos.index' },
        { label: 'Equipamentos', link: 'Enologia.Manager.Equipamentos.index' },
        { label: 'Editar Subcategoria', link: 'Enologia.Manager.Equipamentos.Subcategorias.editar', params: { id: equipamento.id }},
        { label: 'Produtos', link: 'Enologia.Manager.Equipamentos.Produtos.index', params: { id: equipamento.equipamento_subcategoria_id }},
    ];

    const inputItems = [
        [{ titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'texto', max: 120 }, { titulo: 'Subcategoria', name: 'equipamento_subcategoria_id', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'select', options: equipamentosSubcategorias }],
        [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: true, 'toolbar': ['Bold', 'Italic'], max: 1080 }],
        [{ titulo: 'Detalhes', name: 'detalhes', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: true, 'toolbar': ['Bold', 'Italic'], max: 1080 }],
        [{ titulo: 'Imagem', name: 'img', tamanho: 'col-span-12 lg:col-span-7', tipo: 'imagem', crop: true, largura: 1000, altura: 715, imagem: equipamento.imagem }, { titulo: 'Destaque', name: 'destaque', tamanho: 'col-span-2', tipo: 'check' }],
        [{ titulo: 'Catálogo', name: 'arq', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'arquivo', arquivo: equipamento.catalogo ? route('Enologia.Manager.Equipamentos.Produtos.baixarArquivo', { id: equipamento.id, video: 'catalogo' }) : false }, { titulo: 'Vídeo', name: 'vid', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'video', arquivo: equipamento.video ? route('Enologia.Manager.Equipamentos.Produtos.baixarArquivo', { id: equipamento.id, video: 'video-demonstrativo' }) : false }]
    ];

    const handleSubmit = (e) => {
        e.preventDefault();
        const idioma_url = new URLSearchParams(window.location.search).get('lang');

        post(route('Enologia.Manager.Equipamentos.Produtos.atualizar', {id: equipamento.id, lang: idioma_url}), {
            preserveScroll: true,
        });
        console.log(data);

        console.log(errors);
    };

    const onChange = (name, value) => {
        setData(prevData => ({
            ...prevData,
            [name]: value
        }));
    };

    const handleImageCrop = (croppedImage, fileExtenstion, name) => {
        setData(prevData => ({
            ...prevData,
            [name]: croppedImage
        }));
    };
    
    return (
        <AdminLayout>
            <Breadcrumb icon={faHdd} items={breadcrumbItems} current="Editar" idioma={idioma.codigo} idiomas={idiomas} id={equipamento.id} />

            <div className="mb-6 rounded-sm border border-stroke bg-white px-5 py-5 shadow-md">
                <div className="mt-10">
                    <form onSubmit={handleSubmit}>
                        {inputItems.map((group, groupIndex) => (
                            <div key={groupIndex} className="grid grid-cols-12 gap-x-6">
                                {group.map((input, index) => (
                                    <div key={index} className={`w-full ${input.tamanho}`}>
                                        <FormGroup
                                            input={input}
                                            idioma={idioma}
                                            value={data[input.name]}
                                            onChange={onChange}
                                            handleImageCrop={handleImageCrop}
                                        />
                                        {errors[input.name] && <p className="text-sm text-red-500 -mt-5 mb-3">{errors[input.name]}</p>}
                                    </div>
                                ))}
                            </div>
                        ))}

                        <div className="flex items-center justify-end">
                            <Link href={route('Enologia.Manager.Equipamentos.Produtos.index', {id: equipamento.equipamento_subcategoria_id})} className="flex items-center w-fit rounded-lg border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100">
                                <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                                Voltar
                            </Link>

                            <button
                                type="submit"
                                className="block relative w-fit rounded-lg border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200"
                            >   
                                <FontAwesomeIcon icon={faSave} className="text-slate-700 mr-2" />
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AdminLayout>
    );
};

export default Page;