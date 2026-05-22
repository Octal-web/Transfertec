import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faBoxOpen } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Enologia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Enologia/Manager/PageSettings';
import { FormContent } from '@/Components/Enologia/Manager/FormContent';
import { BlockContent } from '@/Components/Enologia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, insumosCategorias, insumosSubcategorias, marcas } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentCategories = {
        nome: ['Categorias', 'categoria'],
        controller: 'Insumos.Categorias',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: insumosCategorias
    };

    const contentSubcategories = {
        nome: ['Subcategorias', 'subccategoria'],
        controller: 'Insumos.Subcategorias',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: insumosSubcategorias
    };

    const contentBrands = {
        nome: ['Marcas', 'marca'],
        controller: 'Marcas',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: marcas
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faBoxOpen} items={breadcrumbItems} current="Insumos" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentCategories} />

            <BlockContent content={contentSubcategories} />

            <BlockContent content={contentBrands} />
        </AdminLayout>
    );
};

export default Page;
