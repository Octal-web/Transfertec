import { useRef, useEffect } from 'react';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Autoplay } from 'swiper/modules';
import 'swiper/swiper-bundle.css';

import { Reveal } from './Reveal';

export const ProjectTransform = ({ content, images }) => {
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
    }, [images]);

    return (
        <section className="bg-neutral-100 pt-16 pb-20 md:pt-20 md:pb-24">
            <div className="container max-w-large">
                <div className="grid md:grid-cols-2 xl:items-center gap-16">
                    <Reveal direction="left">
                        <h1 className="text-5xl sm:text-6xl text-neutral-900 font-light max-w-xl mb-10">{content.titulo}</h1>
                        <div className="font-secondary max-2xl:text-sm text-neutral-700 max-w-xl text-justify" dangerouslySetInnerHTML={{ __html: content.texto }} />
                    </Reveal>

                    <Reveal className="relative ml-2" direction="right">
                        {images ? (
                            <>
                                <Swiper
                                    ref={swiperRef}
                                    slidesPerView={1}
                                    modules={[Navigation, Autoplay]}
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
                                    autoplay={{ delay: 10000 }}
                                >
                                    {images.map((image, index) => (
                                        <SwiperSlide key={index}>
                                            <div className="relative">
                                                <img src={image.imagem} className="w-full aspect-[35/38]" alt="Imagem" />

                                                <div className="absolute inset-0 bg-eng-secondary opacity-5 mix-blend-multiply" />
                                            </div>
                                        </SwiperSlide>
                                    ))}
                                </Swiper>

                                <div className="max-md:hidden absolute z-[12] bottom-0 left-0 -translate-x-1/2">
                                    <div className="flex justify-between">
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
                            </>
                        ) : null}
                    </Reveal>
                </div>
            </div>
        </section>
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