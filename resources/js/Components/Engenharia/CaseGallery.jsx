import React, { useRef, useEffect, useState } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/swiper-bundle.css';

export const CaseGallery = ({ slides }) => {
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

    return (
        <section className="relative pt-20 pb-24 md:pb-36">
            <div className="absolute left-0 right-0 bottom-0 h-60 bg-eng-primary" />
            <div className="container max-w-large">
                <div className="relative">
                    <Swiper
                        slidesPerView={1}
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
                        className="[&_+_div_.swiper-pagination-bullet]:!mx-0 [&_+_div_.swiper-pagination-bullet]:!bg-transparent [&_+_div_.swiper-pagination-bullet]:border [&_+_div_.swiper-pagination-bullet]:!border-white [&_+_div_.swiper-pagination-bullet]:border-2 [&_+_div_.swiper-pagination-bullet]:w-3 [&_+_div_.swiper-pagination-bullet]:h-3 [&_+_div_.swiper-pagination-bullet]:opacity-100 [&_+_div_.swiper-pagination-bullet.swiper-pagination-bullet-active]:!bg-white"
                        ref={swiperRef}
                    >
                        {slides.map((slide, index) => (
                            <SwiperSlide key={slide.id}>
                                <div className="relative">
                                    <img
                                        className=""
                                        alt="Slide"
                                        src={slide.imagem}
                                    />
                                </div>
                            </SwiperSlide>
                        ))}
                    </Swiper>

                    <div className="relative container max-w-large">
                        <div className="absolute -bottom-16 md:-bottom-20 left-0 right-0 z-10 flex justify-center items-center">
                            <div className="flex flex-wrap w-full justify-center items-center space-x-4">
                                <span
                                    ref={prevButtonRef}
                                    className="relative w-7 h-7 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-1.5 before:w-4 before:h-4 before:border-t-2 before:border-l-2 before:-rotate-45 before:border-white"
                                ></span>
                                <div
                                    ref={paginationRef}
                                    className="!w-fit flex items-center !space-x-2 md:!space-x-4"
                                ></div>
                                <span
                                    ref={nextButtonRef}
                                    className="relative w-7 h-7 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-1.5 before:w-4 before:h-4 before:border-b-2 before:border-r-2 before:-rotate-45 before:border-white"
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};