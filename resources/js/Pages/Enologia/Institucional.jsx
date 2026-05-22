import { usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import DefaultLayout from '@/Layouts/EnologiaLayout';

import { AboutBanner } from '@/Components/Enologia/AboutBanner';
import { AboutText } from '@/Components/Enologia/AboutText';
import { AboutTimeline } from '@/Components/Enologia/AboutTimeline';
import { AboutPrinciples } from '@/Components/Enologia/AboutPrinciples';

const Page = () => {
    const { acontecimentos, conteudos } = usePage().props;

    return (
        <DefaultLayout>
            <AboutBanner content={conteudos[0]} />

            <AboutText content={conteudos[1]} />

            <AboutTimeline items={acontecimentos} />
            
            <AboutPrinciples content={conteudos[2]} items={[conteudos[3], conteudos[4], conteudos[5]]} />
        </DefaultLayout>
    );
};

export default Page;
