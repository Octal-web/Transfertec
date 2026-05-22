import { usePage } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { CaseBanner } from '@/Components/Engenharia/CaseBanner';
import { CaseBlockItem } from '@/Components/Engenharia/CaseBlockItem';
import { CaseGallery } from '@/Components/Engenharia/CaseGallery';

const Page = () => {
    const { etapas, caseCliente } = usePage().props;

    return (
        <DefaultLayout>
            <CaseBanner content={caseCliente} />
            {caseCliente.blocos.length ? (
                caseCliente.blocos.map((bloco, index) => (
                    <CaseBlockItem
                        caseClient={caseCliente.nome}
                        key={index}
                        block={bloco}
                        index={index}
                        reverse={index % 2 === 1}
                    />
                ))
            ) : null}
            {caseCliente.imagens.length ? (
                <CaseGallery slides={caseCliente.imagens} />
            ) : null}
        </DefaultLayout>
    );
};

export default Page;
