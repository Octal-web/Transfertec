import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faNewspaper } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Engenharia/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Engenharia/Manager/PageSettings';
import { FormContent } from '@/Components/Engenharia/Manager/FormContent';
import { BlockContent } from '@/Components/Engenharia/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, posts, postsCategorias } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentPosts = {
        nome: ['Posts', 'post'],
        controller: 'Posts',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: posts
    };

    const contentCategories = {
        nome: ['Categorias', 'categoria'],
        controller: 'Posts.Categorias',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: postsCategorias
    };
    
    return (
        <AdminLayout>
            <Breadcrumb icon={faNewspaper} items={breadcrumbItems} current="News" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />
            
            <BlockContent content={contentPosts} />

            <BlockContent content={contentCategories} />
        </AdminLayout>
    );
};

export default Page;
