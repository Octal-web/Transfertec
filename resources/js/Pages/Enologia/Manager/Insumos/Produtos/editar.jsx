import React from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faBoxOpen, faSave, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Enologia/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, idiomas, insumo, insumosSubcategorias, marcas } = usePage().props;
    
    const { data, setData, post, processing, errors } = useForm(insumo);

    const breadcrumbItems = [
        { label: 'Home', link: 'Enologia.Manager.Insumos.index' },
        { label: 'Insumos', link: 'Enologia.Manager.Insumos.index' },
        { label: 'Editar Subcategoria', link: 'Enologia.Manager.Insumos.Subcategorias.editar', params: { id: insumo.id }},
    ];

    const inputItems = [
        [{ titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 md:col-span-8', tipo: 'texto', max: 120 }],
        [{ titulo: 'Subcategoria', name: 'insumo_subcategoria_id', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'select', options: insumosSubcategorias }, { titulo: 'Marca', name: 'marca_id', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'select', options: marcas }],
        [{ titulo: 'Utilidade', name: 'utilidade', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 120 }],
        [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: true, 'toolbar': ['Bold', 'Italic'], max: 1080 }],
        [{ titulo: 'Detalhes', name: 'detalhes', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: true, 'toolbar': ['Bold', 'Italic', 'List'], max: 1080 }],
        [{ titulo: 'Imagem', name: 'img', tamanho: 'col-span-12 lg:col-span-7', tipo: 'imagem', crop: true, largura: 1200, altura: 1200, imagem: insumo.imagem }, { titulo: 'Destaque', name: 'destaque', tamanho: 'col-span-2', tipo: 'check' }],
        [{ titulo: 'Ficha Técnica', name: 'arq_tec', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'arquivo', arquivo: insumo.ficha_tecnica ? route('Enologia.Manager.Insumos.Produtos.baixarArquivo', { id: insumo.id, video: 'ficha-tecnica' }) : false }, { titulo: 'Ficha de Segurança', name: 'arq_seg', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'arquivo', arquivo: insumo.ficha_de_seguranca ? route('Enologia.Manager.Insumos.Produtos.baixarArquivo', { id: insumo.id, video: 'ficha-de-seguranca' }) : false }]
    ];

    const handleSubmit = (e) => {
        e.preventDefault();
        const idioma_url = new URLSearchParams(window.location.search).get('lang');

        post(route('Enologia.Manager.Insumos.Produtos.atualizar', {id: insumo.id, lang: idioma_url}), {
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
            <Breadcrumb icon={faBoxOpen} items={breadcrumbItems} current="Editar" idioma={idioma.codigo} idiomas={idiomas} id={insumo.id} />

            <div className="mb-6 rounded-sm border border-stroke bg-white px-5 py-5 shadow-md">
                <div className="mt-12">
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
                            <Link href={route('Enologia.Manager.Insumos.Produtos.index', {id: insumo.insumo_subcategoria_id})} className="flex items-center w-fit rounded-lg border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100">
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