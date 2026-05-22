import React, { useEffect, useRef } from 'react';
import { usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { HomeSlides } from '@/Components/Engenharia/HomeSlides';
import { HomeSectors } from '@/Components/Engenharia/HomeSectors';
import { HomeCases } from '@/Components/Engenharia/HomeCases';
import { HomeTestimonies } from '@/Components/Engenharia/HomeTestimonies';
import { HomeClients } from '@/Components/Engenharia/HomeClients';
import { HomePosts } from '@/Components/Engenharia/HomePosts';

const Page = () => {
    const { slides, setores, casesClientes, depoimentos, clientes, posts, conteudos } = usePage().props;
    const sectorsBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(sectorsBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: sectorsBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <DefaultLayout>
            <HomeSlides slides={slides} />

            <div
                ref={sectorsBgRef}
                className="relative max-sm:bg-cover"
                style={{
                    backgroundImage: `url(/eng/site/img/sectors-bg.jpg)`,
                }}
            >
                <div className="absolute inset-0 bg-gradient-to-b from-transparent from-40% to-white to-80%" />
                <HomeSectors sectors={setores} content={conteudos[0]} />

                <HomeCases casesClients={casesClientes} content={conteudos[1]} />

                {/* <HomeTestimonies testimonies={depoimentos} content={conteudos[2]} /> */}
            </div>

            <HomeClients clients={clientes} />

            <HomePosts posts={posts} />
        </DefaultLayout>
    );
};

export default Page;
