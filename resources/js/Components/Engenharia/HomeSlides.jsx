import React, { useRef, useEffect, useState } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/swiper-bundle.css';

export const HomeSlides = ({ slides }) => {
    const swiperRef = useRef(null);
    const prevButtonRef = useRef(null);
    const nextButtonRef = useRef(null);
    const paginationRef = useRef(null);

    const [activeIndex, setActiveIndex] = useState(0);
    const [swiperInstance, setSwiperInstance] = useState(null);

    const handleSlideChange = (swiper) => {
        setActiveIndex(swiper.realIndex);
    };

    const onSwiper = (swiper) => {
        setSwiperInstance(swiper);
        setActiveIndex(swiper.realIndex);
    };

    useEffect(() => {
        if (swiperInstance && prevButtonRef.current && nextButtonRef.current && paginationRef.current) {
            swiperInstance.params.navigation.prevEl = prevButtonRef.current;
            swiperInstance.params.navigation.nextEl = nextButtonRef.current;
            swiperInstance.params.pagination.el = paginationRef.current;
            
            swiperInstance.navigation.init();
            swiperInstance.navigation.update();
            
            swiperInstance.pagination.init();
            swiperInstance.pagination.update();
        }
    }, [swiperInstance, prevButtonRef.current, nextButtonRef.current, paginationRef.current]);

    useEffect(() => {
        return () => {
            if (swiperInstance) {
                swiperInstance.destroy(true, true);
            }
        };
    }, []);

    const getBackgroundGradient = () => {
        if (typeof window !== 'undefined') {
            return window.innerWidth >= 768
                ? "linear-gradient(90deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 70%)"
                : "linear-gradient(2deg, rgb(0 0 0 / 67%) 0%, rgba(84, 84, 84, 0) 102%)";
        }
        return "linear-gradient(90deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 70%)";
    };

    return (
        <div className="relative">
            <Swiper
                slidesPerView={1}
                allowTouchMove={false}
                effect="fade"
                navigation={{
                    prevEl: prevButtonRef.current,
                    nextEl: nextButtonRef.current,
                }}
                pagination={{
                    el: paginationRef.current,
                    clickable: true,
                    renderBullet: function (index, className) {
                        return `<span class="${className}"></span>`;
                    },
                }}
                autoplay={{ delay: 10000 }}
                loop
                onSwiper={onSwiper}
                onSlideChange={handleSlideChange}
                modules={[Navigation, Pagination, Autoplay, EffectFade]}
                className="[&_+_div_.swiper-pagination-bullet]:border [&_+_div_.swiper-pagination-bullet]:!border-white [&_+_div_.swiper-pagination-bullet]:border-2 [&_+_div_.swiper-pagination-bullet]:w-3 [&_+_div_.swiper-pagination-bullet]:h-3 [&_+_div_.swiper-pagination-bullet]:opacity-100 [&_+_div_.swiper-pagination-bullet.swiper-pagination-bullet-active]:bg-white"
                ref={swiperRef}
            >
                {slides.map((slide, index) => (
                    <SwiperSlide key={slide.id}>
                        <div className="relative z-[1] h-[calc(100vh_-_145px)] flex items-center">
                            {slide.tipo === 'imagem' && (
                                <div className="absolute inset-0 bg-cover bg-center">
                                    <picture>
                                        <source
                                            media="(max-width: 767px)"
                                            srcSet={slide.imagem_mobile}
                                        />
                                        <img
                                            className="w-full h-full object-cover"
                                            alt="Slide"
                                            src={slide.imagem}
                                        />
                                    </picture>
                                </div>
                            )}
                            {slide.tipo === 'video' && (
                                <video
                                    className="absolute inset-0 w-full h-full object-cover"
                                    autoPlay
                                    muted
                                    loop
                                    playsInline
                                >
                                    <source
                                        media="(min-width: 768px)"
                                        src={slide.video}
                                        type="video/mp4"
                                    />
                                    <source
                                        media="(max-width: 767px)"
                                        src={slide.video_mobile}
                                        type="video/mp4"
                                    />
                                    <p>Your browser does not support the video tag.</p>
                                </video>
                            )}

                            <div
                                className="absolute inset-0"
                                style={{
                                    background: getBackgroundGradient()
                                }}
                            />

                            <div className="absolute inset-0 bg-eng-primary mix-blend-color-dodge opacity-40" />

                            <div className="container max-w-large h-full">
                                <div
                                    className={`flex flex-col relative w-full h-full md:w-[70%] xl:w-1/2 max-w-[474px] justify-end pb-16 2xl:pb-36 transition-opacity duration-1000 ease-in-out z-[1] ${
                                        activeIndex === index
                                            ? 'animate-fade-in-down'
                                            : 'opacity-0'
                                    }`}
                                >
                                    {slide.titulo && (
                                        <h2 className="text-4xl sm:text-5xl 2xl:text-6xl font-medium text-white leading-[1.1] mb-5" dangerouslySetInnerHTML={{ __html: slide.titulo }} />
                                    )}
                                    {slide.descricao && (
                                        <div className="font-secondary text-white text-xl 2xl:text-2xl font-light max-w-sm mb-8">
                                            <p>{slide.descricao}</p>
                                        </div>
                                    )}
                                    {slide.link && (
                                        <a href={slide.link} className="bg-eng-primary w-fit px-8 py-3 rounded-lg text-xl md:text-3xl text-white font-medium tracking-tight transition-all hover:scale-105 hover:shadow" target="_blank" rel="noopener noreferrer">{slide.texto_botao}</a>
                                    )}
                                </div>
                            </div>
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>

            <div className="relative container max-w-large">
                <div className="absolute bottom-16 md:bottom-40 2xl:bottom-50 max-md:w-full left-0 z-10 opacity-80 max-md:ml-0 max-2xl:ml-12">
                    <div className="container max-w-large">
                        <div className="flex md:flex-col items-center md:justify-between">
                            <span
                                ref={prevButtonRef}
                                className="relative w-7 h-7 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-1.5 before:w-4 before:h-4 before:border-t-2 before:border-l-2 before:-rotate-45 md:before:rotate-45 before:border-white"
                            ></span>

                            <div
                                ref={paginationRef}
                                className="flex md:flex-col items-center max-md:!space-x-4 md:!space-y-4 max-md:mx-5 md:my-5"
                            ></div>

                            <span
                                ref={nextButtonRef}
                                className="relative w-7 h-7 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-1.5 before:w-4 before:h-4 before:border-b-2 before:border-r-2 before:-rotate-45 md:before:rotate-45 before:border-white"
                            ></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};