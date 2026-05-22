import { useEffect, useState, useRef } from "react";
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const AutomationItemNoImage = ({ proccess, index, reverse }) => {
    const [isMdUp, setIsMdUp] = useState(false);
    const sectionRef = useRef(null);

    useEffect(() => {
        const checkSize = () => setIsMdUp(window.innerWidth >= 768);
        checkSize();
        window.addEventListener('resize', checkSize);
        return () => window.removeEventListener('resize', checkSize);
    }, []);

    const layouts = {
        1: {
            bgClassList: 'bg-eno-secondary',
            textClassList: 'text-white',
            beforeClassList: 'bg-eno-secondary'
        },
        2: {
            bgClassList: 'bg-white',
            textClassList: 'text-slate-900',
            beforeClassList: 'bg-eno-primary'
        },
        3: {
            bgClassList: 'bg-neutral-100',
            textClassList: 'text-slate-900',
            beforeClassList: 'bg-eno-primary'
        }
    };

    useEffect(() => {
        setTimeout(() => {
            if (window.location.hash === `#${proccess.slug}` && sectionRef.current) {
                sectionRef.current.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 100);
    }, [proccess.slug]);

    return (
        <section 
            ref={sectionRef}
            id={proccess.slug}
            className="pt-16 sm:pt-24 2xl:pt-30"
        >
            <div className="container max-w-large">
                <h3 className={`text-3xl md:text-4xl leading-tight max-sm:tracking-tight 2xl:text-[45px] text-neutral-900 font-light md:mb-4 2xl:mb-6`}>
                    {proccess.nome}
                </h3>

                <div className="grid grid-cols-1 md:grid-cols-2 items-center gap-16 py-20">
                    {reverse && isMdUp ? (
                        <>
                            <Reveal direction="left" className="relative">
                                <div className={`absolute -top-20 -bottom-20 right-0 w-screen md:w-[50vw] ${layouts[proccess.layout].bgClassList}`} />

                                <div className={`relative font-secondary ${layouts[proccess.layout].textClassList} text-justify before:!bg-eno-secondary max-w-xl`} dangerouslySetInnerHTML={{ __html: proccess.descricao }} />
                            </Reveal>

                            <Reveal direction="right" className={`relative pl-12 md:pl-20 before:absolute before:top-3 before:left-0 before:w-8 md:before:w-14 before:h-0.5 before:bg-white before:!${layouts[proccess.layout].beforeClassList} max-w-lg`}>
                                <h3 className="2xl:text-xl font-bold mb-4">{proccess.utilidade}</h3>
                                <ul className={` text-justify [&_li>p]:contents [&_li+li]:mt-2 2xl:[&_li+li]:mt-5`} dangerouslySetInnerHTML={{ __html: proccess.detalhes }} />
                            </Reveal>
                        </>
                    ) : (
                        <>
                            <Reveal direction="right" className={`relative max-md:order-1 pl-12 md:pl-20 before:absolute before:top-3 before:left-0 before:w-8 md:before:w-14 before:h-0.5 before:bg-white before:!${layouts[proccess.layout].beforeClassList} max-w-lg`}>
                                <h3 className="2xl:text-xl font-bold mb-4">{proccess.utilidade}</h3>
                                <ul className={` text-justify [&_li>p]:contents [&_li+li]:mt-2 2xl:[&_li+li]:mt-5`} dangerouslySetInnerHTML={{ __html: proccess.detalhes }} />
                            </Reveal>

                            <Reveal direction="left" className="relative">
                                <div className={`absolute -top-5 md:-top-20 -bottom-5 md:-bottom-20 left-1/2 max-md:-translate-x-1/2 md:-left-16 w-screen md:w-[calc(50vw_+_4rem)] ${layouts[proccess.layout].bgClassList}`} />

                                <div className={`relative font-secondary ${layouts[proccess.layout].textClassList} text-justify before:!bg-eno-secondary max-w-xl ${proccess.layout != 2 ? 'max-md:py-10' : ''}`} dangerouslySetInnerHTML={{ __html: proccess.descricao }} />
                            </Reveal>
                        </>
                    )}
                </div>
            </div>
        </section>
    );
};