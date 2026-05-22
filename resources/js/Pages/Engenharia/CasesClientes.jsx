import React, { useState, useEffect } from 'react';
import { usePage, router } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { CasesBanner } from '@/Components/Engenharia/CasesBanner';
import { CasesList } from '@/Components/Engenharia/CasesList';

const Page = () => {
    const { conteudos, casesClientes: initialCases } = usePage().props;
    const [casesClientes, setCasesClientes] = useState(initialCases);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setLoading(false);
    }, []);

    const handlePageChange = (url) => {
        setLoading(true);

        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['casesClientes'],
            onSuccess: (page) => {
                setCasesClientes(page.props.casesClientes);
                setLoading(false);
            }
        });
    };

    return (
        <DefaultLayout>
            <CasesBanner content={conteudos[0]} />

            <CasesList
                casesClientes={casesClientes.data}
                loading={loading}
                links={casesClientes.links} 
                totalPages={casesClientes.last_page}
                onPageChange={handlePageChange}
            />

        </DefaultLayout>
    );
};

export default Page;
