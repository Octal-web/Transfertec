import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCogs } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Enologia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Enologia/Manager/PageSettings';
import { FormContent } from '@/Components/Enologia/Manager/FormContent';
import { BlockContent } from '@/Components/Enologia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, processos } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];
    const contentProcess = {
        nome: ['Processos', 'processo'],
        controller: 'Processos',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: processos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faCogs} items={breadcrumbItems} current="Automação" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentProcess} />
        </AdminLayout>
    );
};

export default Page;
