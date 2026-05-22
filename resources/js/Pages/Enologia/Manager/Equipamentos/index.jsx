import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHdd } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Enologia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Enologia/Manager/PageSettings';
import { FormContent } from '@/Components/Enologia/Manager/FormContent';
import { BlockContent } from '@/Components/Enologia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, equipamentosCategorias, equipamentosSubcategorias } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentCategories = {
        nome: ['Categorias', 'categoria'],
        controller: 'Equipamentos.Categorias',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: equipamentosCategorias
    };

    const contentSubcategories = {
        nome: ['Subcategorias', 'subccategoria'],
        controller: 'Equipamentos.Subcategorias',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: equipamentosSubcategorias
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faHdd} items={breadcrumbItems} current="Equipamentos" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentCategories} />

            <BlockContent content={contentSubcategories} />
        </AdminLayout>
    );
};

export default Page;
