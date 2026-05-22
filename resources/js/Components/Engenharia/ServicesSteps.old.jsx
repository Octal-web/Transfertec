import React, { useRef, useEffect, useState } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/swiper-bundle.css';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const ServicesSteps = ({ steps }) => {
    const swiperRef = useRef(null);
    const prevButtonRef = useRef(null);
    const nextButtonRef = useRef(null);

    const [activeIndex, setActiveIndex] = useState(0);
    const [swiperInstance, setSwiperInstance] = useState(null);

    const servicesBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(servicesBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: servicesBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    const handleSlideChange = (swiper) => {
        setActiveIndex(swiper.realIndex);
    };

    useEffect(() => {
        if (swiperRef.current && prevButtonRef.current && nextButtonRef.current) {
            swiperRef.current.params.navigation.prevEl = prevButtonRef.current;
            swiperRef.current.params.navigation.nextEl = nextButtonRef.current;
            swiperRef.current.navigation.init();
            swiperRef.current.navigation.update();
        }
    }, [steps]);

    useEffect(() => {
        return () => {
            if (swiperInstance) {
                swiperInstance.destroy(true, true);
            }
        };
    }, []);

    const SplitHeading = ({ text }) => {
        const lines = text.split(' ');
        const firstLine = [];
        const restLines = [];
        
        let currentLine = '';
        let firstLineDone = false;
        
        for (const word of lines) {
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
            className="py-16 md:py-24"
            style={{
                backgroundImage: `url(/eng/site/img/product-bg.jpg)`,
            }}
        >
            <Reveal className="" direction="right">
                <div className="container max-w-large">
                    <h1 className="w-2/3 text-4xl sm:text-5xl 2xl:text-6xl font-light text-white mb-20">SOLUÇÃO <span className="font-bold italic">Turn-Key</span></h1>
                    <div className="relative">
                        <Swiper
                            breakpoints={{
                                0: {
                                    slidesPerView: 1.2,
                                    spaceBetween: 20,
                                },
                                768: {
                                    slidesPerView: 1.6,
                                    spaceBetween: 30,
                                },
                                1280: {
                                    slidesPerView: 1.9,
                                    spaceBetween: 30,
                                },
                            }}
                            navigation={{
                                prevEl: prevButtonRef.current,
                                nextEl: nextButtonRef.current,
                            }}
                            onBeforeInit={(swiper) => {
                                swiperRef.current = swiper;
                                swiper.params.navigation.prevEl = prevButtonRef.current;
                                swiper.params.navigation.nextEl = nextButtonRef.current;
                            }}
                            autoplay={{ delay: 3000 }}
                            onSlideChange={handleSlideChange}
                            modules={[Navigation, Autoplay]}
                            className="!overflow-visible"
                            ref={swiperRef}
                        >
                            {steps.map((step, index) => (
                                <SwiperSlide key={index}>
                                    <div className="relative">
                                        <div className={`relative h-1 bg-eng-primary mb-14 sm:mb-20${index !== 0 ? ' -ml-[31px]' : ''}`}>
                                            {activeIndex > index && (
                                                <div className="absolute inset-0 bg-white" />
                                            )}
                                            {activeIndex >= index && (
                                                <div className={`absolute h-full bg-white ${index !== 0 ? 'w-[calc(50%_+_20px)] md:w-[calc(25%_+_30px)]' : 'w-[calc(50%_+_20px)] md:w-[calc(28%_+_30px)]'}`} />
                                            )}
                                            <span className={`absolute w-12 md:w-16 h-12 md:h-16 text-3xl md:text-4xl font-bold flex items-center justify-center rounded-full transition-all -translate-y-1/2 ${activeIndex < index ? 'bg-eng-primary text-white' : 'bg-white text-eng-primary' } ${index !== 0 ? 'max-md:-translate-x-2 left-1/2 md:left-[28%]' : 'max-md:-translate-x-6 left-1/2 md:left-[25%]'}`}>{index + 1}</span>
                                        </div>
                                        <div className={`md:flex md:items-start transition-all${activeIndex === index ? '' : ' opacity-60' }`}>
                                            <img className="md:w-7/12 rounded-xl border border-neutral-700" src={step.imagem} />

                                            <div className="md:w-5/12 max-md:pt-4 md:pl-6 2xl:pl-8">
                                                <div>
                                                    <SplitHeading text={step.nome} />

                                                    <div className="font-secondary text-white font-light max-w-sm md:mb-8">
                                                        <p>{step.descricao}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </SwiperSlide>
                            ))}
                        </Swiper>

                        <div className="md:hidden absolute -top-30 right-0">
                            <div className="container max-w-large">
                                <div className="max-[520px]:-translate-y-6 flex">
                                    <span
                                        ref={prevButtonRef}
                                        className="relative w-10 h-10 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-1.5 before:w-6 before:h-6 before:border-t-2 before:border-l-2 before:-rotate-45 before:border-white"
                                    ></span>

                                    <span
                                        ref={nextButtonRef}
                                        className="relative w-10 h-10 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-1.5 before:w-6 before:h-6 before:border-b-2 before:border-r-2 before:-rotate-45 before:border-white"
                                    ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Reveal>
        </section>
    );
};