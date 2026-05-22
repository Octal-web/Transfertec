import { usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import DefaultLayout from '@/Layouts/EnologiaLayout';

import { HomeSlides } from '@/Components/Enologia/HomeSlides';
import { HomeSolutions } from '@/Components/Enologia/HomeSolutions';
import { HomeQuality } from '@/Components/Enologia/HomeQuality';
import { HomeProducts } from '@/Components/Enologia/HomeProducts';
import { HomePartners } from '@/Components/Enologia/HomePartners';
import { HomePosts } from '@/Components/Enologia/HomePosts';

const Page = () => {
    const { slides, solucoes, insumos, equipamentos, parceiros, posts, conteudos } = usePage().props;
    return (
        <DefaultLayout>
            <HomeSlides slides={slides} />

            <HomeSolutions content={conteudos[0]} solutions={solucoes} />

            <HomeQuality content={conteudos[1]} />

            <HomeProducts items={insumos} type="Insumos" />
            
            <HomeProducts items={equipamentos} type="Equipamentos" />

            <HomePartners partners={parceiros} />

            <HomePosts posts={posts} />
        </DefaultLayout>
    );
};

export default Page;
