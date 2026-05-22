import { useState, useRef, useEffect } from 'react';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/swiper-bundle.css';

import { Reveal } from './Reveal';

export const AboutTimeline = ({ items }) => {
    if (!items.length) return;

    const prevButtonRef = useRef(null);
    const nextButtonRef = useRef(null);
    const swiperImageRef = useRef(null);
    const swiperTextRef = useRef(null);
    const swiperYearsRef = useRef(null);
    const [activeIndex, setActiveIndex] = useState(null);

    // Função para sincronizar todos os swipers
    const syncAllSwipers = (targetIndex) => {
        if (swiperImageRef.current) {
            swiperImageRef.current.swiper.slideTo(targetIndex);
        }
        if (swiperTextRef.current) {
            swiperTextRef.current.swiper.slideTo(targetIndex);
        }
        if (swiperYearsRef.current) {
            swiperYearsRef.current.swiper.slideTo(targetIndex);
        }
        setActiveIndex(targetIndex);
    };

    const handleBottomSlideClick = (index) => {
        syncAllSwipers(index);
    };

    useEffect(() => {
        setTimeout(() => {
            setActiveIndex(0);
        }, 500);
    }, []);

    useEffect(() => {
        if (swiperImageRef.current && prevButtonRef.current && nextButtonRef.current) {
            swiperImageRef.current.params.navigation.prevEl = prevButtonRef.current;
            swiperImageRef.current.params.navigation.nextEl = nextButtonRef.current;
            swiperImageRef.current.navigation.init();
            swiperImageRef.current.navigation.update();
        }
    }, [items]);

    return (
        <section className="relative pt-16 md:pt-20 before:absolute before:left-0 before:top-0 before:right-0 before:h-10 md:before:h-[11.8em] before:bg-neutral-100">
            <div className="relative container max-w-large">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 pb-28">
                    <Reveal direction="right">
                        <div className="md:hidden w-fit bg-eno-secondary px-6 py-2 flex items-center justify-center gap-2 text-white text-2xl mx-auto mt-20 mb-10">
                            <img src="/eno/site/img/timeline-icon.png" className="w-10" alt="Timeline icon" />
                            Linha do Tempo
                        </div>

                        <Swiper
                            ref={swiperImageRef}
                            slidesPerView={1}
                            effect="fade"
                            allowTouchMove={false}
                            modules={[EffectFade, Navigation]}
                            onBeforeInit={(swiper) => {
                                swiperImageRef.current = swiper;
                                swiper.params.navigation.prevEl = prevButtonRef.current;
                                swiper.params.navigation.nextEl = nextButtonRef.current;
                            }}
                            navigation={{
                                prevEl: prevButtonRef.current,
                                nextEl: nextButtonRef.current,
                            }}
                            onSlideChange={(swiper) => {
                                const newIndex = swiper.activeIndex;
                                if (swiperTextRef.current) {
                                    swiperTextRef.current.swiper.slideTo(newIndex);
                                }
                                if (swiperYearsRef.current) {
                                    swiperYearsRef.current.swiper.slideTo(newIndex);
                                }
                                setActiveIndex(newIndex);
                            }}
                        >
                            {items.map((item, index) => (
                                <SwiperSlide key={index}>
                                    <div className="">
                                        <img src={item.imagem} alt={`Imagem ${item.ano}`} />
                                    </div>
                                </SwiperSlide>
                            ))}
                        </Swiper>
                    </Reveal>

                    <Reveal direction="left" className="flex flex-col">
                        <div className="max-md:hidden w-fit bg-eno-secondary px-6 py-2 flex items-center gap-2 text-white text-4xl mt-20 mb-auto">
                            <img src="/eno/site/img/timeline-icon.png" className="w-10" alt="Timeline icon" />
                            Linha do Tempo
                        </div>
                        <div>
                            <Swiper
                                ref={swiperTextRef}
                                slidesPerView={1}
                                effect="fade"
                                allowTouchMove={false}
                                modules={[EffectFade]}
                            >
                                {items.map((item, index) => (
                                    <SwiperSlide key={index} className="!h-auto">
                                        <div className="bg-white h-full">
                                            <h1 className="font-secondary text-5xl xl:text-7xl text-neutral-900 my-2">{item.ano}</h1>
                                            <h3 className="text-2xl md:text-3xl text-neutral-900 mb-6">{item.titulo}</h3>
                                            <div className="font-secondary max-2xl:text-sm text-neutral-900 max-w-xl text-justify mb-10" dangerouslySetInnerHTML={{ __html: item.texto }} />
                                        </div>
                                    </SwiperSlide>
                                ))}
                            </Swiper>
                        </div>

                        <div className="flex mb-6 sm:mb-10 max-sm:scale-90 origin-left">
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

                        <div className="relative md:w-[calc(50vw_-_2rem)] bg-eno-primary before:content-[''] before:absolute before:top-1/2 before:left-0 before:right-0 before:h-px before:bg-white">
                            <Swiper
                                ref={swiperYearsRef}
                                modules={[Navigation]}
                                slidesPerView={5.4}
                                spaceBetween={50}
                                loop={false}
                                breakpoints={{
                                    320: {
                                        slidesPerView: 2.2,
                                    },
                                    480: {
                                        slidesPerView: 3.2,
                                    },
                                    768: {
                                        slidesPerView: 4.2,
                                    },
                                    1024: {
                                        slidesPerView: 5.2,
                                    },
                                    1280: {
                                        slidesPerView: 5.4,
                                    }
                                }}
                                className="my-4 2xl:my-6"
                            >
                                {items.map((item, index) => (
                                    <SwiperSlide
                                        key={item.id}
                                        onClick={() => handleBottomSlideClick(index)}
                                        className={`cursor-pointer ${index === 0 ? 'ml-0' : ''} ${index === items.length - 1 ? 'mr-0' : ''}`}
                                    >
                                        <h4 className={`bg-eno-primary text-xl text-center text-white${index === activeIndex ? ' font-bold' : ''} transition-all hover:text-opacity-75`}>{item.ano}</h4>
                                    </SwiperSlide>
                                ))}
                            </Swiper>
                        </div>
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