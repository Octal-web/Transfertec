import React, { useEffect } from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGears, faSave, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Enologia/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma } = usePage().props;

    const { data, setData, post, processing, errors } = useForm();

    const breadcrumbItems = [
        { label: 'Automação', link: 'Enologia.Manager.Automacao.index' },
        { label: 'Processos', link: 'Enologia.Manager.Automacao.index' },
    ];

    const layoutOptions = [
        { value: '1', label: 'Fundo vermelho escuro' },
        { value: '2', label: 'Fundo branco' },
        { value: '3', label: 'Fundo cinza' }
    ];

    const inputItems = [
        [{ titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 120 }],
        [{ titulo: 'Utilidade', name: 'utilidade', tamanho: 'col-span-12 lg:col-span-4', tipo: 'texto', max: 120 }, { titulo: 'Layout', name: 'layout', tamanho: 'col-span-12 lg:col-span-4', tipo: 'select', options: layoutOptions }],
        [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: true, 'toolbar': ['Bold', 'Italic'], max: 1080 }],
        [{ titulo: 'Detalhes', name: 'detalhes', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: true, 'toolbar': ['Bold', 'Italic', 'List'], max: 820 }],
    ];

    const initializeData = (inputItems) => {
        let initialData = {};
        inputItems.forEach(group => {
            group.forEach(item => {
                initialData[item.name] = item.tipo === 'check' ? false : '';
            });
        });
        return initialData;
    };

    useEffect(() => {
        const initialData = initializeData(inputItems);
        setData(initialData);
    }, []); 

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('Enologia.Manager.Processos.novo'), {
            preserveScroll: true
        });
        console.log(data);

        console.log(errors);
    };

    const onChange = (name, value) => {
        setData(name, value);
    };

    const handleImageCrop = (croppedImage, fileExtenstion, name) => {
        setData(prevData => ({
            ...prevData,
            [name]: croppedImage
        }));
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faGears} items={breadcrumbItems} current="Adicionar" idioma={idioma.codigo} />

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
                            <Link href={route('Enologia.Manager.Automacao.index')} className="flex items-center w-fit rounded-lg border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100">
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