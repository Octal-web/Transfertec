import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHome } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { PageSettings } from '@/Components/Engenharia/Manager/PageSettings';
import { FormContent } from '@/Components/Engenharia/Manager/FormContent';
import { BlockContent } from '@/Components/Engenharia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, slides, setores, depoimentos, clientes } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentSlides = {
        nome: ['Slides', 'slide'],
        controller: 'Slides',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: slides,
        addParametros: ['imagem', 'video']
    };

    const contentSectors = {
        nome: ['Setores', 'setor'],
        controller: 'Setores',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: setores
    };

    const contentTestimonies = {
        nome: ['Depoimentos', 'depoimento'],
        controller: 'Depoimentos',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: depoimentos
    };

    const contentClients = {
        nome: ['Clientes', 'cliente'],
        controller: 'Clientes',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: clientes
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faHome} items={breadcrumbItems} current="Home" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <BlockContent content={contentSlides} />
            
            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentSectors} />

            <FormContent content={conteudos[1]} full={true} idioma={idioma.codigo} />

            <FormContent content={conteudos[2]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentTestimonies} />

            <BlockContent content={contentClients} />
        </AdminLayout>
    );
};

export default Page;
