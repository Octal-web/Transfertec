import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCircleInfo } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Enologia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Enologia/Manager/PageSettings';
import { FormContent } from '@/Components/Enologia/Manager/FormContent';
import { BlockContent } from '@/Components/Enologia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, acontecimentos } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentTimeline = {
        nome: ['Linha do Tempo', 'acontecimento'],
        controller: 'Acontecimentos',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: acontecimentos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faCircleInfo} items={breadcrumbItems} current="Institucional" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <FormContent content={conteudos[1]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentTimeline} />

            <FormContent content={conteudos[2]} full={false} idioma={idioma.codigo} />

            <div className="grid grid-cols-1 gap-x-6 md:grid-cols-3">
                <FormContent content={conteudos[3]} full={false} idioma={idioma.codigo} />

                <FormContent content={conteudos[4]} full={false} idioma={idioma.codigo} />

                <FormContent content={conteudos[5]} full={false} idioma={idioma.codigo} />
            </div>
        </AdminLayout>
    );
};

export default Page;
