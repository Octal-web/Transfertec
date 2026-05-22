import React, { useEffect } from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faBuilding, faSave, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Engenharia/Manager/Inputs/FormGroup';

const Page = () => {
    const { id, idioma } = usePage().props;
    console.log(id)
    const { data, setData, post, processing, errors } = useForm();

    const breadcrumbItems = [
        { label: 'Home', link: 'Engenharia.Manager.Home.index' },
        { label: 'Cases', link: 'Engenharia.Manager.Home.index' },
        { label: 'Editar Bloco', link: 'Engenharia.Manager.Cases.editar', params: { id: id }},
    ];

    const inputItems = [
        [{ titulo: 'Texto', name: 'texto', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: true, 'toolbar': ['Bold', 'Italic'], max: 1080 }],
        [{ titulo: 'Imagem', name: 'img', tamanho: 'col-span-12 lg:col-span-8', tipo: 'imagem', crop: true, largura: 800, altura: 720 }]
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
        post(route('Engenharia.Manager.Cases.Blocos.novo', {id: id}), {
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
            <Breadcrumb icon={faBuilding} items={breadcrumbItems} current="Adicionar" idioma={idioma.codigo} />

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
                            <Link href={route('Engenharia.Manager.Home.index')} className="flex items-center w-fit rounded-lg border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100">
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