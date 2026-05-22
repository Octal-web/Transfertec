import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEnvelope } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Engenharia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Engenharia/Manager/PageSettings';
import { FormContent } from '@/Components/Engenharia/Manager/FormContent';
import { BlockContent } from '@/Components/Engenharia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, contatos } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentContacts = {
        nome: ['Contatos', 'contato'],
        controller: 'Contato',
        imagens: false,
        imgClass: '',
        editavel: false,
        conteudos: contatos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faEnvelope} items={breadcrumbItems} current="Contato" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <BlockContent content={contentContacts} />
        </AdminLayout>
    );
};

export default Page;
