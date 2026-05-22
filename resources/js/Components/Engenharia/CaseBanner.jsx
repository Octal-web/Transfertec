import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const CaseBanner = ({ content }) => {
    const caseBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(caseBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: caseBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);
    return (
        <section 
            ref={caseBgRef}
            className="relative w-full aspect-[1.8/1] md:aspect-[5/1] xl:aspect-[6/1] max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:300%] sm:bg-[length:170%] bg-[60%] xl:bg-[length:100%]"
            style={{
                backgroundImage: `url(/eng/site/img/case-bg.jpg)`,
            }}
        >
            <div className="absolute inset-0">
                <div className="container max-w-large h-full">
                    <div className="flex flex-col justify-center h-full">
                        <h1 className="text-3xl md:text-5xl xl:text-6xl 2xl:text-7xl text-white flex items-center mb-4">{content.titulo_topo}</h1>
                        <div className="sm:text-lg xl:text-xl text-white">{content.descricao_topo}</div>
                    </div>
                </div>
            </div>
        </section>
    );
};