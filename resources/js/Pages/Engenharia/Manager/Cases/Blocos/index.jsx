import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faList } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Engenharia/Manager/BlockContent';

const Page = () => {
    // Content
    const { idioma, idiomas, caseCliente } = usePage().props;

    const breadcrumbItems = [
        { label: 'Home', link: 'Engenharia.Manager.Home.index' },
        { label: 'Cases', link: 'Engenharia.Manager.Home.index' },
        { label: caseCliente.nome, link: 'Engenharia.Manager.Cases.editar', params: { id: caseCliente.id }},
    ];

    const contentBlocks = {
        nome: ['Blocos de Texto', 'bloco'],
        controller: 'Cases.Blocos',
        imagens: true,
        imgClass: '',
        addId: caseCliente.id,
        editavel: true,
        conteudos: caseCliente.blocos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faList} items={breadcrumbItems} current="Blocos de Texto" idioma={idioma.codigo} idiomas={idiomas} />

            <BlockContent content={contentBlocks} />
        </AdminLayout>
    );
};

export default Page;
