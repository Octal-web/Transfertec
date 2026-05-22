import { usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import DefaultLayout from '@/Layouts/EnologiaLayout';

import { EngineerBar } from '@/Components/Enologia/EngineerBar';
import { ProjectSlides } from '@/Components/Enologia/ProjectSlides';
import { ProjectPrinciples } from '@/Components/Enologia/ProjectPrinciples';
import { ProjectTransform } from '@/Components/Enologia/ProjectTransform';
import { ProjectSteps } from '@/Components/Enologia/ProjectSteps';

const Page = () => {
    const { imagensGaleria, etapas, conteudos } = usePage().props;

    return (
        <DefaultLayout>
            <EngineerBar />

            <ProjectSlides slides={imagensGaleria[conteudos[0].id]} />

            <ProjectPrinciples items={[conteudos[1], conteudos[2], conteudos[3]]} />

            <ProjectTransform content={conteudos[4]} images={imagensGaleria[conteudos[4].id]} />

            <ProjectSteps steps={etapas} />
        </DefaultLayout>
    );
};

export default Page;
