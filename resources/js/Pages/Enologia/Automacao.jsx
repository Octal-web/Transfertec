import { usePage } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/EnologiaLayout';

import { EngineerBar } from '@/Components/Enologia/EngineerBar';
import { AutomationBanner } from '@/Components/Enologia/AutomationBanner';
import { AutomationVideo } from '@/Components/Enologia/AutomationVideo';
import { AutomationItem } from '@/Components/Enologia/AutomationItem';
import { AutomationItemNoImage } from '@/Components/Enologia/AutomationItemNoImage';

const Page = () => {
    const { processos, conteudos } = usePage().props;
    return (
        <DefaultLayout>
            <EngineerBar />

            <AutomationBanner content={conteudos[0]} />
            <AutomationVideo url={conteudos[0].video} />

            {processos.map((processo, index) => (
                processo.imagens.length ? (
                    <AutomationItem
                        key={index}
                        proccess={processo}
                        index={index}
                        reverse={index % 2 === 1}
                    />
                ) : (
                    <AutomationItemNoImage
                        key={index}
                        proccess={processo}
                        index={index}
                        reverse={index % 2 === 1}
                    />
                )
            ))}
        </DefaultLayout>
    );
};

export default Page;
