import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const ServicesBanner = ({ content }) => {
    const servicesBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(servicesBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: servicesBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <section 
            ref={servicesBgRef}
            className="relative w-full h-0 pb-[100%] md:pb-[calc(100%_/_3)] min-[1422px]:pb-[425px] max-[570px]:bg-[length:250%] sm:bg-[length:170%] bg-[60%] xl:bg-[length:100%]"
            style={{
                backgroundImage: `url(/eng/site/img/about-bg.jpg)`,
            }}
        >
            <div className="absolute inset-0 flex items-center justify-center h-full w-full">
                <div className="container max-w-large">
                    <Reveal direction="bottom">
                        <h2 className="text-4xl sm:text-5xl text-white text-center mb-6 sm:mb-10">{content.titulo}</h2>
                        <p className="font-secondary xl:text-lg max-w-5xl text-white text-center mx-auto mb-10">{content.texto}</p>
                    </Reveal>
                </div>
            </div>
        </section>
    );
};