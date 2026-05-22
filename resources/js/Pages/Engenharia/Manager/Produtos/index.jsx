import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFlask } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Engenharia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Engenharia/Manager/PageSettings';
import { FormContent } from '@/Components/Engenharia/Manager/FormContent';
import { BlockContent } from '@/Components/Engenharia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, produtos } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];
    const contentProducts = {
        nome: ['Produtos', 'produto'],
        controller: 'Produtos',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: produtos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faFlask} items={breadcrumbItems} current="Produtos" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />
            
            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentProducts} />

            <FormContent content={conteudos[1]} full={true} idioma={idioma.codigo} />

            <div className="grid grid-cols-1 gap-x-6 md:grid-cols-3">
                <FormContent content={conteudos[2]} full={false} idioma={idioma.codigo} />

                <FormContent content={conteudos[3]} full={false} idioma={idioma.codigo} />

                <FormContent content={conteudos[4]} full={false} idioma={idioma.codigo} />
            </div>
        </AdminLayout>
    );
};

export default Page;
