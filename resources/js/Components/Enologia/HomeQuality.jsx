import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const HomeQuality = ({ content }) => {
    const qualityBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(qualityBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: qualityBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <section 
            ref={qualityBgRef}
            className="pt-56 pb-30 max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] bg-[60%] xl:bg-[length:100%]"
            style={{
                backgroundImage: `url(/eno/site/img/quality-bg.jpg)`,
            }}
        >
            <div className="container max-w-large">
                <Reveal direction="bottom" className="">
                    <h2 className="text-4xl sm:text-5xl text-white text-center mb-6 sm:mb-8 max-w-2xl mx-auto">{content.titulo}</h2>
                    <p className="font-secondary max-sm:text-justify text-white text-center max-w-3xl mx-auto mb-10">{content.texto}</p>
                </Reveal>
            </div>
        </section>
    );
};