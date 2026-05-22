import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faBoxOpen } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Enologia/Manager/BlockContent';

const Page = () => {
    // Content
    const { idioma, idiomas, insumoSubcategoria } = usePage().props;

    const breadcrumbItems = [
        { label: 'Home', link: 'Enologia.Manager.Home.index' },
        { label: 'Subcategorias', link: 'Enologia.Manager.Home.index' },
        { label: insumoSubcategoria.nome, link: 'Enologia.Manager.Insumos.Subcategorias.editar', params: { id: insumoSubcategoria.id }},
    ];

    const contentProducts = {
        nome: ['Produtos', 'produto'],
        controller: 'Insumos.Produtos',
        imagens: true,
        imgClass: '',
        addId: insumoSubcategoria.id,
        editavel: true,
        conteudos: insumoSubcategoria.insumos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faBoxOpen} items={breadcrumbItems} current="Insumos" idioma={idioma.codigo} />

            <BlockContent content={contentProducts} />
        </AdminLayout>
    );
};

export default Page;
