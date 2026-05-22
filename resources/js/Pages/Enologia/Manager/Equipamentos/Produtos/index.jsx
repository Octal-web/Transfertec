import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHdd } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEnologiaLayout';
import { Breadcrumb } from '@/Components/Enologia/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Enologia/Manager/BlockContent';

const Page = () => {
    // Content
    const { idioma, idiomas, equipamentoSubcategoria } = usePage().props;

    const breadcrumbItems = [
        { label: 'Home', link: 'Enologia.Manager.Home.index' },
        { label: 'Subcategorias', link: 'Enologia.Manager.Home.index' },
        { label: equipamentoSubcategoria.nome, link: 'Enologia.Manager.Equipamentos.Subcategorias.editar', params: { id: equipamentoSubcategoria.id }},
    ];

    const contentProducts = {
        nome: ['Produtos', 'produto'],
        controller: 'Equipamentos.Produtos',
        imagens: true,
        imgClass: '',
        addId: equipamentoSubcategoria.id,
        editavel: true,
        conteudos: equipamentoSubcategoria.equipamentos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faHdd} items={breadcrumbItems} current="Equipamentos" idioma={idioma.codigo} />

            <BlockContent content={contentProducts} />
        </AdminLayout>
    );
};

export default Page;
