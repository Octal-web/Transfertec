import { useState, useRef, useEffect } from 'react';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/swiper-bundle.css';

export const AutomationGallery = ({ slides, reverse }) => {
    
    if (!slides.length) return;
    const prevButtonRef = useRef(null);
    const nextButtonRef = useRef(null);
    const swiperRef = useRef(null);

    useEffect(() => {
        if (swiperRef.current && prevButtonRef.current && nextButtonRef.current) {
            swiperRef.current.params.navigation.prevEl = prevButtonRef.current;
            swiperRef.current.params.navigation.nextEl = nextButtonRef.current;
            swiperRef.current.navigation.init();
            swiperRef.current.navigation.update();
        }
    }, [slides]);

    return (
        <div className="relative">
            <Swiper
                ref={swiperRef}
                slidesPerView={1}
                effect="fade"
                allowTouchMove={false}
                modules={[EffectFade, Navigation]}
                onBeforeInit={(swiper) => {
                    swiperRef.current = swiper;
                    swiper.params.navigation.prevEl = prevButtonRef.current;
                    swiper.params.navigation.nextEl = nextButtonRef.current;
                }}
                loop={true}
                navigation={{
                    prevEl: prevButtonRef.current,
                    nextEl: nextButtonRef.current,
                }}
            >
                {slides.map((slide, index) => (
                    <SwiperSlide key={index}>
                        <div className="">
                            <img src={slide.imagem} alt="Imagem" />
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>

            <div className={`absolute max-md:-bottom-1 bottom-0 flex max-sm:scale-90 max-md:left-1/2 max-md:right-auto max-md:-translate-x-1/2 ${reverse ? 'right-0 translate-x-1/2' : 'left-0 -translate-x-1/2'} origin-left z-[1]`}>
                <button
                    ref={prevButtonRef}
                    className="group w-20 2xl:w-32 h-16 2xl:h-[4.5em] flex items-center justify-center bg-white border border-neutral-200 transition ease-out duration-200 disabled:opacity-60 hover:border-eno-primary hover:bg-eno-primary"
                    aria-label="Slide anterior"
                >
                    <ArrowIcon className="fill-neutral-600 opacity-30 rotate-180 transition-all group-hover:opacity-100 group-hover:fill-white" />
                </button>
                <button
                    ref={nextButtonRef}
                    className="group w-20 2xl:w-32 h-16 2xl:h-[4.5em] flex items-center justify-center bg-white border border-neutral-200 transition ease-out duration-200 disabled:opacity-60 hover:border-eno-primary hover:bg-eno-primary"
                    aria-label="Próximo slide"
                >
                    <ArrowIcon className="fill-neutral-600 opacity-30 transition-all group-hover:opacity-100 group-hover:fill-white" />
                </button>
            </div>
        </div>
    );
};

const ArrowIcon = ({ className }) => {
    return (
        <svg
            width="61.185"
            height="39.808" 
            viewBox="0 0 61.185 39.808"
            className={className}
            xmlns="http://www.w3.org/2000/svg"
        >
            <path d="M60.7,18.734,42.448.485a1.655,1.655,0,0,0-2.34,2.34L55.537,18.25H1.654a1.654,1.654,0,0,0,0,3.308H55.537L40.108,36.984a1.655,1.655,0,0,0,2.34,2.34L60.7,21.074a1.656,1.656,0,0,0,0-2.34" />
        </svg>
    )
};