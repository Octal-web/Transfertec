import { useEffect, useState, useRef } from "react";
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const CaseBlockItem = ({ caseClient, block, index, contents, reverse }) => {
    const [isMdUp, setIsMdUp] = useState(false);
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
                    strokeDashoffset: "-392",
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

    return (
        <section className={`${index === 0 ? 'pt-16 sm:pt-24 2xl:pt-30' : 'pt-16 xl:pt-10'} pb-10 sm:pb-24 2xl:pb-30 [&_+_.section]:pt-10`}>
            <div className="container max-w-large">
                <div className="grid md:grid-cols-2 items-center gap-16">
                    {reverse && isMdUp ? (
                        <>
                            <Reveal className="relative" direction="right">
                                <img src={block.imagem} className="rounded-lg" />
                                <div className="absolute inset-0 bg-eng-primary mix-blend-overlay rounded-lg opacity-10" />
                                <svg
                                    className="absolute w-[calc(100%_+_5rem)] xl:w-[calc(100%_+_10rem)] h-[calc(100%_+_5rem)] xl:h-[calc(100%_+_10rem)] -inset-10 md:-inset-20 stroke-eng-primary rounded-lg -scale-100"
                                    viewBox="0 0 100 92"
                                    preserveAspectRatio="none"
                                >
                                    <rect
                                        ref={borderRefReverse}
                                        x="5"
                                        y="5"
                                        width="90"
                                        height="82"
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
                                <div className="font-secondary text-justify max-2xl:text-sm text-eng-tertiary max-w-lg" dangerouslySetInnerHTML={{ __html: block.texto }} />
                            </Reveal>
                        </>
                    ) : (
                        <>
                            <Reveal direction="left">
                                {index === 0 && (
                                    <h3 className="text-3xl xl:text-4xl 2xl:text-5xl text-eng-tertiary mb-6">{caseClient}</h3>
                                )}
                                <div className="font-secondary text-justify max-2xl:text-sm text-eng-tertiary max-w-lg" dangerouslySetInnerHTML={{ __html: block.texto }} />
                            </Reveal>

                            <Reveal className="relative" direction="right">
                                <img src={block.imagem} className="rounded-lg" />
                                <div className="absolute inset-0 bg-eng-primary mix-blend-overlay rounded-lg opacity-10" />
                                <svg
                                    className="absolute w-[calc(100%_+_5rem)] xl:w-[calc(100%_+_10rem)] h-[calc(100%_+_5rem)] xl:h-[calc(100%_+_10rem)] -inset-10 md:-inset-20 stroke-eng-primary rounded-lg -scale-100 rotate-180"
                                    viewBox="0 0 100 92"
                                    preserveAspectRatio="none"
                                >
                                    <rect
                                        ref={borderRef}
                                        x="5"
                                        y="5"
                                        width="90"
                                        height="82"
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
    );
};