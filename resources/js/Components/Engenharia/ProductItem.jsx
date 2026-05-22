import { useEffect, useState, useRef } from "react";
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const ProductItem = ({ product, index, contents, reverse }) => {
    const [isMdUp, setIsMdUp] = useState(false);
    const sectionRef = useRef(null);
    const borderRef = useRef(null);
    const borderRefReverse = useRef(null);

    useEffect(() => {
        const checkSize = () => setIsMdUp(window.innerWidth >= 768);
        checkSize();
        window.addEventListener('resize', checkSize);
        return () => window.removeEventListener('resize', checkSize);
    }, []);

    useEffect(() => {
        const currentRef = (reverse && isMdUp) ? borderRefReverse.current : borderRef.current;
        
        if (currentRef) {
            gsap.fromTo(
                currentRef,
                { 
                    strokeDasharray: "90 220",
                    strokeDashoffset: "-220"
                },
                {
                    strokeDasharray: "50 284",
                    strokeDashoffset: "-399",
                    duration: 2,
                    ease: "power2.out",
                    delay: 0.6,
                    scrollTrigger: {
                        trigger: currentRef,
                        start: "top 80%",
                        toggleActions: "play none none reverse",
                    },
                }
            );
        }
    }, [reverse, isMdUp]);

    const layouts = {
        1: {
            bgClassList: 'bg-eng-tertiary',
            textClassList: 'text-white',
            spanClassList: 'text-white',
            beforeClassList: 'bg-white'
        },
        2: {
            bgClassList: 'bg-white',
            textClassList: 'text-slate-900',
            spanClassList: 'text-eng-primary font-bold',
            beforeClassList: 'bg-eng-primary'
        },
        3: {
            bgClassList: 'bg-neutral-300',
            textClassList: 'text-slate-900',
            spanClassList: 'text-eng-primary font-bold',
            beforeClassList: 'bg-eng-primary'
        }
    };

    useEffect(() => {
        setTimeout(() => {
            if (window.location.hash === `#${product.slug}` && sectionRef.current) {
                sectionRef.current.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 100);
    }, [product.slug]);

    return (
        <>
            <section 
                ref={sectionRef}
                id={product.slug}
                className={`pt-16 pb-10 sm:py-24 2xl:py-30 ${layouts[product.layout].bgClassList}`}
            >
                <div className="container max-w-large">
                    <div className="grid md:grid-cols-2 items-start gap-16">
                        {reverse && isMdUp ? (
                            <>
                                <Reveal className="relative" direction="right">
                                    <img src={product.imagem} className="rounded-lg" />
                                    <div className="absolute inset-0 bg-eng-primary mix-blend-overlay rounded-lg opacity-10" />
                                    <svg
                                        className="absolute w-[calc(100%_+_5rem)] xl:w-[calc(100%_+_10rem)] h-[calc(100%_+_5rem)] xl:h-[calc(100%_+_10rem)] -inset-10 md:-inset-20 stroke-eng-primary rounded-lg -scale-100"
                                        viewBox="0 0 100 100"
                                        preserveAspectRatio="none"
                                    >
                                        <rect
                                        ref={borderRefReverse}
                                            x="5"
                                            y="5"
                                            width="90"
                                            height="90"
                                            strokeWidth="1.4"
                                            rx="2"
                                            ry="2"
                                            fill="none"
                                            strokeDasharray="90 220"
                                            strokeDashoffset="-220"
                                        />
                                    </svg>
                                </Reveal>

                                <Reveal direction="left">
                                    <h1 className={`text-5xl 2xl:text-6xl ${layouts[product.layout].textClassList} font-light mb-6 2xl:mb-10 max-w-sm`}>
                                        {product.nome.split(' ').map((word, index) => (
                                            <span key={index} className={index > 0 ? layouts[product.layout].spanClassList : ''}>
                                                {word + ' '}
                                            </span>
                                        ))}
                                    </h1>
                                    <div className={`font-secondary text-justify ${layouts[product.layout].textClassList} before:!bg-eng-primary max-w-xl pb-14 2xl:pb-20`} dangerouslySetInnerHTML={{ __html: product.descricao }} />

                                    <Reveal direction="left" delay="0.8" className={`relative pl-12 md:pl-20 before:absolute before:top-3 before:left-0 before:w-8 md:before:w-14 before:h-0.5 before:bg-white before:!${layouts[product.layout].beforeClassList} max-w-lg`}>
                                        <h3 className={`${layouts[product.layout].textClassList} 2xl:text-xl font-bold mb-4`}>{product.setor}</h3>
                                        <ul className={`${layouts[product.layout].textClassList} text-justify [&_li>p]:contents [&_li+li]:mt-2 2xl:[&_li+li]:mt-5`} dangerouslySetInnerHTML={{ __html: product.detalhes }} />
                                    </Reveal>
                                </Reveal>
                            </>
                        ) : (
                            <>
                                <Reveal direction="left">
                                    <h1 className={`text-5xl 2xl:text-6xl ${layouts[product.layout].textClassList} font-light mb-6 2xl:mb-10 max-w-sm`}>
                                        {product.nome.split(' ').map((word, index) => (
                                            <span key={index} className={index > 0 || product.nome.split(' ').length === 1 ? layouts[product.layout].spanClassList : ''}>
                                                {word + ' '}
                                            </span>
                                        ))}
                                    </h1>
                                    <div className={`font-secondary text-justify ${layouts[product.layout].textClassList} max-w-xl pb-14 2xl:pb-20`} dangerouslySetInnerHTML={{ __html: product.descricao }} />

                                    <Reveal direction="left" delay="0.8" className={`relative pl-12 md:pl-20 before:absolute before:top-3 before:left-0 before:w-8 md:before:w-14 before:h-0.5 before:${layouts[product.layout].beforeClassList} max-w-xl`}>
                                        <h3 className={`${layouts[product.layout].textClassList} 2xl:text-xl font-bold mb-4`}>{product.setor}</h3>
                                        <ul className={`${layouts[product.layout].textClassList} text-justify [&_li>p]:contents [&_li+li]:mt-2 2xl:[&_li+li]:mt-5`} dangerouslySetInnerHTML={{ __html: product.detalhes }} />
                                    </Reveal>
                                </Reveal>

                                <Reveal className="relative" direction="right">
                                    <img src={product.imagem} className="rounded-lg" />
                                    <div className="absolute inset-0 bg-eng-primary mix-blend-overlay rounded-lg opacity-10" />
                                    <svg
                                        className="absolute w-[calc(100%_+_5rem)] xl:w-[calc(100%_+_10rem)] h-[calc(100%_+_5rem)] xl:h-[calc(100%_+_10rem)] -inset-10 md:-inset-20 stroke-eng-primary rounded-lg rotate-90"
                                        viewBox="0 0 100 100"
                                        preserveAspectRatio="none"
                                    >
                                        <rect
                                            ref={borderRef}
                                            x="5"
                                            y="5"
                                            width="90"
                                            height="90"
                                            strokeWidth="1.4"
                                            rx="2"
                                            ry="2"
                                            fill="none"
                                            strokeDasharray="90 220"
                                            strokeDashoffset="-220"
                                        />
                                    </svg>
                                </Reveal>
                            </>
                        )}
                    </div>
                </div>
            </section>

            {/* index === 1 && (
                <section className="relative bg-eng-tertiary pt-10 pb-16 sm:pb-24 after:absolute after:bottom-0 after:left-0 after:right-0 after:h-1/5 sm:after:h-1/4 after:bg-eng-primary">
                    <div className="container max-w-medium">
                        <Reveal direction="bottom">
                            <h2 className="text-4xl md:text-5xl text-white text-center mb-10">{contents[1].titulo}</h2>
                            <p className="font-secondary max-w-3xl text-white text-center mx-auto mb-10">{contents[1].texto}</p>
                        </Reveal>

                        <Reveal direction="bottom" className="font-secondary text-xl text-white font-medium text-center mb-16">Os diferencias do nosso produto estão suportados em 3 importantes pilares:</Reveal>

                        <div className="relative grid grid-cols-3 gap-2 sm:gap-10 2xl:gap-20 z-[1]">
                            <Reveal delay="1" direction="left">
                                <div className="relative flex flex-col items-center h-full bg-white max-w-80 mx-auto rounded-lg px-4 py-8 md:p-14 shadow-md shadow">
                                    <img src={contents[2].imagem} className="mb-6 mx-auto" />
                                    <h4 className="text-eng-tertiary sm:text-2xl font-medium text-center uppercase">{contents[2].titulo}</h4>
                                </div>
                            </Reveal>

                            <Reveal delay="2" direction="left">
                                <div className="relative flex flex-col items-center h-full bg-white max-w-80 mx-auto rounded-lg px-4 py-8 md:p-14 shadow-md shadow">
                                    <img src={contents[3].imagem} className="mb-6 mx-auto" />
                                    <h4 className="text-eng-tertiary sm:text-2xl font-medium text-center uppercase">{contents[3].titulo}</h4>
                                </div>
                            </Reveal>

                            <Reveal delay="3" direction="left">
                                <div className="relative flex flex-col items-center h-full bg-white max-w-80 mx-auto rounded-lg px-4 py-8 md:p-14 shadow-md shadow">
                                    <img src={contents[4].imagem} className="mb-6 mx-auto" />
                                    <h4 className="text-eng-tertiary sm:text-2xl font-medium text-center uppercase">{contents[4].titulo}</h4>
                                </div>
                            </Reveal>
                        </div>
                    </div>
                </section>
            ) */}
        </>
    );
};