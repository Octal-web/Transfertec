import React, { useRef, useEffect, useState } from 'react';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export const ProjectSteps = ({ steps }) => {
    const [activeIndex, setActiveIndex] = useState(0);
    const servicesBgRef = useRef(null);
    const stepRefs = useRef([]);
    const timelineRef = useRef(null);

    useEffect(() => {
        gsap.registerPlugin(ScrollTrigger);
        
        gsap.fromTo(servicesBgRef.current, 
        {
            backgroundPositionY: '0%',
        },
        {
            backgroundPositionY: '10%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: servicesBgRef.current,
                start: 'top bottom',
                end: 'bottom bottom',
                scrub: true
            }
        });

        timelineRef.current = gsap.timeline({
            scrollTrigger: {
                trigger: servicesBgRef.current,
                start: 'top center',
                end: 'bottom center',
                scrub: 1,
                onUpdate: (self) => {
                    const progress = self.progress;
                    const currentStep = Math.min(Math.floor(progress * steps.length), steps.length - 1);
                    setActiveIndex(currentStep);
                }
            }
        });

        timelineRef.current.fromTo('.progress-line', 
            { height: '0%' },
            { height: '100%', duration: 1, ease: 'none' }
        );

        setTimeout(() => {
            ScrollTrigger.refresh();
        }, 100);

        stepRefs.current.forEach((ref, index) => {
            if (ref) {
                ScrollTrigger.create({
                    trigger: ref,
                    start: 'top center+=100',
                    end: 'bottom center-=100',
                    onEnter: () => setActiveIndex(index),
                    onEnterBack: () => setActiveIndex(index),
                });
            }
        });

        return () => {
            ScrollTrigger.getAll().forEach(trigger => trigger.kill());
            if (timelineRef.current) {
                timelineRef.current.kill();
            }
        };
    }, [steps.length]);

    const SplitHeading = ({ text }) => {
        const words = text.split(' ');
        const firstLine = [];
        const restLines = [];
        
        let currentLine = '';
        let firstLineDone = false;
        
        for (const word of words) {
            if (!firstLineDone && (currentLine.length + word.length) < 16) {
                firstLine.push(word);
                currentLine += word + ' ';
            } else {
                if (!firstLineDone) {
                    firstLineDone = true;
                    currentLine = '';
                }
                restLines.push(word);
            }
        }
        
        return (
            <h3 className="text-2xl 2xl:text-3xl text-white leading-[1.1] tracking-tight mb-3 2xl:mb-5 hyphens-auto">
                <span className="font-bold max-md:mr-1.5 md:block">{firstLine.join(' ')}</span>
                {restLines.length > 0 && <span className="font-light">{restLines.join(' ')}</span>}
            </h3>
        );
    };

    return (
        <section 
            ref={servicesBgRef}
            className="py-16 md:py-24 min-h-screen bg-fixed"
            style={{
                backgroundImage: `url(/eno/site/img/steps-bg.jpg)`,
            }}
        >
            <div className="">
                <div className="container max-w-large">
                    <h1 className="w-full md:w-2/3 text-4xl sm:text-5xl 2xl:text-6xl font-light text-white mb-20 text-center md:text-left">
                        SOLUÇÃO <span className="font-bold italic">Turn-Key</span>
                    </h1>
                    
                    <div className="relative md:mx-10">
                        <div className="absolute left-1/2 top-0 bottom-0 w-1 bg-eno-secondary transform -translate-x-1/2 hidden md:block">
                            <div className="progress-line absolute top-0 left-0 w-full bg-white origin-top"></div>
                        </div>

                        <div className="space-y-20 md:space-y-32">
                            {steps.map((step, index) => (
                                <div 
                                    key={index}
                                    ref={el => stepRefs.current[index] = el}
                                    className="relative">
                                    <div className={`absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center text-2xl md:text-3xl font-bold transition-all duration-500
                                        ${activeIndex >= index 
                                            ? 'bg-white text-eno-secondary shadow-lg' 
                                            : 'bg-eno-secondary text-white'
                                        }
                                        hidden md:flex
                                    `}>
                                        {index + 1}
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-20 items-center">
                                        {index % 2 === 0 ? (
                                            <>
                                                <div className={`md:pr-12 transition-all duration-500 ${
                                                    activeIndex === index ? 'opacity-100' : 'opacity-60'
                                                }`}>
                                                    <img 
                                                        className="w-full rounded-xl border border-neutral-700 shadow-2xl" 
                                                        src={step.imagem} 
                                                        alt={step.nome}
                                                    />
                                                </div>

                                                <div className={`md:pl-12 transition-all duration-500 ${
                                                    activeIndex === index ? 'opacity-100' : 'opacity-60'
                                                }`}>
                                                    <div className={`
                                                        w-12 h-12 rounded-full mb-4
                                                        flex items-center justify-center md:hidden
                                                        text-xl font-bold
                                                        transition-all duration-500
                                                        ${activeIndex >= index 
                                                            ? 'bg-white text-eng-primary' 
                                                            : 'bg-eng-primary text-white'
                                                        }
                                                    `}>
                                                        {index + 1}
                                                    </div>

                                                    <SplitHeading text={step.nome} />
                                                    
                                                    <div className="font-secondary text-white font-light">
                                                        <p>{step.descricao}</p>
                                                    </div>
                                                </div>
                                            </>
                                        ) : (
                                            <>
                                                <div className={`md:pr-12 order-1 transition-all duration-500 ${
                                                    activeIndex === index ? 'opacity-100' : 'opacity-60'
                                                }`}>
                                                    <div className={`
                                                        w-12 h-12 rounded-full mb-4
                                                        flex items-center justify-center md:hidden
                                                        text-xl font-bold
                                                        transition-all duration-500
                                                        ${activeIndex >= index 
                                                            ? 'bg-white text-eng-primary' 
                                                            : 'bg-eng-primary text-white'
                                                        }
                                                    `}>
                                                        {index + 1}
                                                    </div>

                                                    <SplitHeading text={step.nome} />
                                                    
                                                    <div className="font-secondary text-white font-light">
                                                        <p>{step.descricao}</p>
                                                    </div>
                                                </div>

                                                <div className={`md:pl-12 md:order-2 transition-all duration-500 ${
                                                    activeIndex === index ? 'opacity-100' : 'opacity-60'
                                                }`}>
                                                    <img 
                                                        className="w-full rounded-xl border border-neutral-700 shadow-2xl" 
                                                        src={step.imagem} 
                                                        alt={step.nome}
                                                    />
                                                </div>
                                            </>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};