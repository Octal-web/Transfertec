import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const AutomationBanner = ({ content }) => {
    const automationBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(automationBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: automationBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <section 
            ref={automationBgRef}
            className="mb-60 md:mb-30 aspect-[8/5] md:aspect-[96/30] xl:aspect-[96/23] max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] bg-[60%] xl:bg-[length:100%]"
            style={{
                backgroundImage: `url(/eno/site/img/automation-bg.jpg)`,
            }}
        >
            <div className="container max-w-large h-full">
                <Reveal direction="top" className="relative h-full">
                    <div className="absolute grid grid-cols-1 md:grid-cols-2 items-center w-full bg-eno-secondary max-[420px]:p-6 p-12 bottom-0 translate-y-60 md:translate-y-30">
                        <h2 className="text-2xl sm:text-3xl md:text-4xl xl:text-5xl text-white max-[420px]:!leading-none max-w-2xl max-md:mb-5 max-w-[550px]">{content.titulo}</h2>
                        <div className="font-secondary max-sm:text-xs text-justify text-white md:ml-4 max-w-[600px]" dangerouslySetInnerHTML={{ __html: content.texto }} />
                    </div>
                </Reveal>
            </div>
        </section>
    );
};