import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faMoneyBillWaveAlt } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Engenharia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Engenharia/Manager/PageSettings';
import { FormContent } from '@/Components/Engenharia/Manager/FormContent';
import { BlockContent } from '@/Components/Engenharia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, orcamentos } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentBudgets = {
        nome: ['Solicitações', 'solicitação'],
        controller: 'Orcamentos',
        imagens: false,
        imgClass: '',
        editavel: false,
        conteudos: orcamentos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faMoneyBillWaveAlt} items={breadcrumbItems} current="Solicite Orçamento" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <BlockContent content={contentBudgets} />
        </AdminLayout>
    );
};

export default Page;
