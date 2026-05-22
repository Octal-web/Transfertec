import { usePage } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { ServicesBanner } from '@/Components/Engenharia/ServicesBanner';
import { ServicesSteps } from '@/Components/Engenharia/ServicesSteps';
import { ServicesVideo } from '@/Components/Engenharia/ServicesVideo';

const Page = () => {
    const { etapas, conteudos } = usePage().props;
    return (
        <DefaultLayout>
            <ServicesBanner content={conteudos[0]} />
            <ServicesVideo url={conteudos[0].video} />
            <ServicesSteps steps={etapas} />
        </DefaultLayout>
    );
};

export default Page;
