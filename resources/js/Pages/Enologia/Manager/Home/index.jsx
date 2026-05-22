import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHome } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { PageSettings } from '@/Components/Enologia/Manager/PageSettings';
import { FormContent } from '@/Components/Enologia/Manager/FormContent';
import { BlockContent } from '@/Components/Enologia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, slides, solucoes, parceiros } = usePage().props;

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

    const contentSolutions = {
        nome: ['Soluções', 'solução'],
        controller: 'Solucoes',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: solucoes
    };

    const contentPartners = {
        nome: ['Parceiros', 'parceiro'],
        controller: 'Parceiros',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: parceiros
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faHome} items={breadcrumbItems} current="Home" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <BlockContent content={contentSlides} />
            
            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentSolutions} />

            <FormContent content={conteudos[1]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentPartners} />
        </AdminLayout>
    );
};

export default Page;
