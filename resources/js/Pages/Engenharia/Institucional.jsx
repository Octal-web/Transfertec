import { usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { AboutBanner } from '@/Components/Engenharia/AboutBanner';
import { AboutText } from '@/Components/Engenharia/AboutText';
import { AboutPrinciples } from '@/Components/Engenharia/AboutPrinciples';
// import { AboutTeam } from '@/Components/Engenharia/AboutTeam';
// import { AboutCertifications } from '@/Components/Engenharia/AboutCertifications';

const Page = () => {
    const { membros, certificacoes, conteudos } = usePage().props;

    return (
        <DefaultLayout>
            <AboutBanner content={conteudos[0]} />

            <AboutText content={conteudos[1]} />
            <AboutPrinciples content={conteudos[2]} items={[conteudos[3], conteudos[4], conteudos[5]]} />
            {/* <AboutTeam content={conteudos[6]} members={membros} />
            <AboutCertifications content={conteudos[7]} certifications={certificacoes} partners={conteudos[8]} /> */}
        </DefaultLayout>
    );
};

export default Page;
