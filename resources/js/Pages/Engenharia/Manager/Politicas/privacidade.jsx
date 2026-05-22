import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFileText } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { PageSettings } from '@/Components/Engenharia/Manager/PageSettings';
import { FormContent } from '@/Components/Engenharia/Manager/FormContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, posts } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    return (
        <AdminLayout>
            <Breadcrumb icon={faFileText} items={breadcrumbItems} current="Política de Privacidade" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />
        </AdminLayout>
    );
};

export default Page;
