import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const ProductCTA = () => {
    const ctaBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(ctaBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: ctaBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <section 
            ref={ctaBgRef}
            className="relative w-full max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:220%] sm:bg-[length:170%] bg-[60%] xl:bg-[length:120%] 2xl:bg-[length:100%] pt-16 pb-20"
            style={{
                backgroundImage: `url(/eng/site/img/cta-bg.jpg)`,
            }}
        >
            <div className="inset- justify-center h-full w-full">
                <div className="container max-w-small">
                    <Reveal direction="bottom">
                        <h2 className="text-4xl sm:text-5xl 2xl:text-[55px] text-white text-center font-bold leading-none mb-6 sm:mx-6">Dê o próximo passo rumo à inovação industrial.</h2>
                        <h4 className="font-secondary text-xl sm:text-2xl 2xl:text-3xl max-w-3xl text-white text-center mx-auto mb-16">Estamos prontos para fazer parte do seu projeto.</h4>
                    </Reveal>
                </div>

                <Reveal direction="bottom" className="">
                    <Link href={route('Engenharia.Contato.index')} className="bg-eng-primary text-xl sm:text-2xl 2xl:text-3xl text-white tracking-tight block w-fit mx-auto px-7 py-3 rounded-lg transition-all hover:scale-105 hover:shadow">Saiba como podemos ajudar</Link>
                </Reveal>
            </div>
        </section>
    );
};