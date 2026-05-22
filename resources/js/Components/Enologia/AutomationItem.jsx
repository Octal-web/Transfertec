import { useEffect, useState, useRef } from "react";
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';
import { AutomationGallery } from './AutomationGallery';

export const AutomationItem = ({ proccess, index, reverse }) => {
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
            beforeClassList: 'bg-white'
        },
        2: {
            bgClassList: 'bg-white',
            textClassList: 'text-neutral-900',
            beforeClassList: 'bg-eno-primary'
        },
        3: {
            bgClassList: 'bg-neutral-100',
            textClassList: 'text-neutral-900',
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
            className={`pt-16 pb-10 sm:py-24 2xl:py-30 ${layouts[proccess.layout].bgClassList}`}
        >
            <div className="container max-w-large">
                <div className="grid grid-cols-1 md:grid-cols-2 items-start gap-16">
                    {reverse && isMdUp ? (
                        <>
                            <Reveal className="relative" direction="right">
                                <AutomationGallery slides={proccess.imagens} reverse={true} />
                                <div className="absolute inset-0 bg-eno-primary mix-blend-overlay rounded-lg opacity-10" />
                            </Reveal>

                            <Reveal direction="left">
                                <h3 className={`text-3xl md:text-4xl leading-tight max-sm:tracking-tight 2xl:text-[45px] font-light ${layouts[proccess.layout].textClassList} font-light mb-6 2xl:mb-10 max-w-xl`}>
                                    {proccess.nome}
                                </h3>
                                <div className={`font-secondary text-justify ${layouts[proccess.layout].textClassList} before:!bg-eno-secondary max-w-xl pb-8 2xl:pb-14`} dangerouslySetInnerHTML={{ __html: proccess.descricao }} />

                                <Reveal direction="left" delay="0.8" className={`relative pl-12 md:pl-20 before:absolute before:top-3 before:left-0 before:w-8 md:before:w-14 before:h-0.5 before:bg-white before:!${layouts[proccess.layout].beforeClassList} max-w-lg`}>
                                    <h3 className={`${layouts[proccess.layout].textClassList} 2xl:text-xl font-bold mb-4`}>{proccess.utilidade}</h3>
                                    <ul className={`${layouts[proccess.layout].textClassList} text-justify [&_li>p]:contents [&_li+li]:mt-2 2xl:[&_li+li]:mt-5`} dangerouslySetInnerHTML={{ __html: proccess.detalhes }} />
                                </Reveal>
                            </Reveal>
                        </>
                    ) : (
                        <>
                            <Reveal direction="left">
                                <h3 className={`text-3xl md:text-4xl leading-tight max-sm:tracking-tight 2xl:text-[45px] font-light ${layouts[proccess.layout].textClassList} font-light mb-6 2xl:mb-10 max-w-xl`}>
                                    {proccess.nome}
                                </h3>
                                <div className={`font-secondary text-justify ${layouts[proccess.layout].textClassList} max-w-xl pb-8 2xl:pb-14`} dangerouslySetInnerHTML={{ __html: proccess.descricao }} />

                                <Reveal direction="left" delay="0.8" className={`relative pl-12 md:pl-20 before:absolute before:top-3 before:left-0 before:w-8 md:before:w-14 before:h-0.5 before:bg-white before:!${layouts[proccess.layout].beforeClassList} max-w-xl`}>
                                    <h3 className={`${layouts[proccess.layout].textClassList} 2xl:text-xl font-bold mb-4`}>{proccess.utilidade}</h3>
                                    <ul className={`${layouts[proccess.layout].textClassList} text-justify [&_li>p]:contents [&_li+li]:mt-2 2xl:[&_li+li]:mt-5 max-w-md`} dangerouslySetInnerHTML={{ __html: proccess.detalhes }} />
                                </Reveal>
                            </Reveal>

                            <Reveal className="relative h-auto" direction="right">
                                <AutomationGallery slides={proccess.imagens} reverse={false} />
                                <div className="absolute inset-0 bg-eno-primary mix-blend-overlay rounded-lg opacity-10" />
                            </Reveal>
                        </>
                    )}
                </div>
            </div>
        </section>
    );
};