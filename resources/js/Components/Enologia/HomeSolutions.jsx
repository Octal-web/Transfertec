import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const HomeSolutions = ({ content, solutions }) => {
    const solutionsBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(solutionsBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: solutionsBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <section 
            ref={solutionsBgRef}
            className="pt-30 max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:182%] bg-[60%] xl:bg-[length:110%] 2xl:bg-[length:100%]"
            style={{
                backgroundImage: `url(/eno/site/img/solutions-bg.jpg)`,
            }}
        >
            <div className="container max-w-large">
                <Reveal direction="bottom" className="md:mb-72 xl:mb-50 2xl:mb-60">
                    <h2 className="text-4xl sm:text-5xl text-eno-secondary text-center mb-4 sm:mb-6 max-w-xl mx-auto sm:px-2">{content.titulo}</h2>
                    <p className="font-secondary max-sm:text-justify max-sm:text-sm text-center max-w-lg mx-auto mb-10">{content.texto}</p>
                </Reveal>

                {solutions && solutions.length ? (
                    <div className="relative grid sm:grid-cols-3 gap-4 2xl:gap-8 -mb-30 z-[1]">
                        {solutions.map((solution, index) => (
                            <Reveal key={index} delay={index * 0.4} direction="bottom">
                                <div className="group relative flex flex-col items-center h-full bg-white px-6 2xl:px-8 py-10 shadow-md transition-all hover:bg-eno-secondary shadow">
                                    <img src={solution.icone} className="mb-4 xl:mb-6 transition-all group-hover:invert group-hover:brightness-0 group-hover:grayscale" />
                                    <h3 className="text-neutral-900 text-3xl 2xl:[text-35px] text-center font-medium max-w-xs mb-4 xl:mb-6 transition-all group-hover:text-gray-200">{solution.titulo}</h3>
                                    <p className="text-sm text-center font-light max-w-sm transition-all group-hover:text-white">{solution.descricao}</p>
                                </div>
                            </Reveal>
                        ))}
                    </div>
                ) : null }
            </div>
        </section>
    );
};