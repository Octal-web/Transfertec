import React, { useRef, useEffect, useState } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/swiper-bundle.css';

export const ProjectSlides = ({ slides }) => {
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
                className="[&_+_div_.swiper-pagination-bullet]:border [&_+_div_.swiper-pagination-bullet]:!border-white [&_+_div_.swiper-pagination-bullet]:border-2 [&_+_div_.swiper-pagination-bullet]:w-3 [&_+_div_.swiper-pagination-bullet]:h-3 [&_+_div_.swiper-pagination-bullet]:opacity-100 [&_+_div_.swiper-pagination-bullet.swiper-pagination-bullet-active]:!border-eno-primary [&_+_div_.swiper-pagination-bullet.swiper-pagination-bullet-active]:bg-eno-primary"
                ref={swiperRef}
            >
                {slides.map((slide, index) => (
                    <SwiperSlide key={slide.id}>
                        <div className="relative z-[1] flex items-center">
                            <div className="w-full">
                                <img
                                    className="w-full aspect-square md:aspect-[2.2] object-cover"
                                    alt="Slide"
                                    src={slide.imagem}
                                />
                            </div>

                            <div
                                className="absolute inset-0"
                                style={{
                                    background: getBackgroundGradient()
                                }}
                            />
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>

            <div className="absolute top-[40%] md:top-1/2 -translate-y-1/2 left-12 xl:left-20 z-10 opacity-80 max-md:ml-0 z-[1]">
                <div className="flex flex-col justify-between">
                    <span
                        ref={prevButtonRef}
                        className="relative w-7 h-7 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-1.5 before:w-4 before:h-4 before:border-t-2 before:border-l-2 before:rotate-45 before:border-white"
                    ></span>

                    <div
                        ref={paginationRef}
                        className="flex flex-col items-center !space-y-4 my-5"
                    ></div>

                    <span
                        ref={nextButtonRef}
                        className="relative w-7 h-7 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-1.5 before:w-4 before:h-4 before:border-b-2 before:border-r-2 before:rotate-45 before:border-white"
                    ></span>
                </div>
            </div>
        </div>
    );
};