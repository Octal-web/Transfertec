import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faClipboard } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Enologia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Enologia/Manager/PageSettings';
import { FormContent } from '@/Components/Enologia/Manager/FormContent';
import { BlockContent } from '@/Components/Enologia/Manager/BlockContent';

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
            <Breadcrumb icon={faClipboard} items={breadcrumbItems} current="Projetos" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <div className="grid grid-cols-1 gap-x-6 md:grid-cols-3">
                <FormContent content={conteudos[1]} full={false} idioma={idioma.codigo} />

                <FormContent content={conteudos[2]} full={false} idioma={idioma.codigo} />

                <FormContent content={conteudos[3]} full={false} idioma={idioma.codigo} />
            </div>

            <FormContent content={conteudos[4]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentSteps} />
        </AdminLayout>
    );
};

export default Page;
