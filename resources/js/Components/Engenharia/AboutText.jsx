import { useEffect, useRef } from "react";
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const AboutText = ({ content }) => {
    const borderRef = useRef(null);

    useEffect(() => {
        gsap.fromTo(
            borderRef.current,
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
                    trigger: borderRef.current,
                    start: "top 80%",
                    toggleActions: "play none none reverse",
                },
            }
        );
    }, []);

    return (
        <section className="bg-neutral-100 pt-16 md:pt-20">
            <div className="container max-w-large">
                <div className="grid md:grid-cols-2 xl:items-end md:gap-16 pb-28">
                    <Reveal direction="left">
                        <h1 className="text-4xl sm:text-5xl text-eng-primary font-light mb-10">{content.titulo}</h1>
                        <div className="font-secondary max-2xl:text-sm text-eng-tertiary max-w-xl text-justify" dangerouslySetInnerHTML={{ __html: content.texto }} />
                    </Reveal>

                    <Reveal className="relative" direction="right">
                        <img src={content.imagem} className="rounded-lg" />
                        <svg
                            className="absolute w-[calc(100%_+_5rem)] xl:w-[calc(100%_+_10rem)] h-[calc(100%_+_5rem)] xl:h-[calc(100%_+_10rem)] -inset-10 xl:-inset-20 stroke-eng-primary rounded-lg rotate-90"
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
                </div>
            </div>
        </section>
    );
};