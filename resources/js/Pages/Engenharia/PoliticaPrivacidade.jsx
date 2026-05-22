import { usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { PrivacyPolicy } from '@/Components/Engenharia/PrivacyPolicy';

const Page = () => {
    const { conteudos } = usePage().props;

    return (
        <DefaultLayout>
            <PrivacyPolicy content={conteudos[0]} />
        </DefaultLayout>
    );
};

export default Page;
