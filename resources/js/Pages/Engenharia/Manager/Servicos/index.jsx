import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCog } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Engenharia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Engenharia/Manager/PageSettings';
import { FormContent } from '@/Components/Engenharia/Manager/FormContent';
import { BlockContent } from '@/Components/Engenharia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, etapas } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentSteps = {
        nome: ['Etapas', 'etapa'],
        controller: 'Etapas',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: etapas
    };


    return (
        <AdminLayout>
            <Breadcrumb icon={faCog} items={breadcrumbItems} current="Serviços" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />
            
            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentSteps} />
        </AdminLayout>
    );
};

export default Page;
