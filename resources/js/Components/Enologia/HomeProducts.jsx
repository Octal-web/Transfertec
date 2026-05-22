import { useRef, useEffect } from 'react';
import { Link } from '@inertiajs/react';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/swiper-bundle.css';

export const HomeProducts = ({ items, type }) => {
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
    }, [items]);

    return (
        <section className="pt-20 2xl:pt-30 pb-10">
            <div className="relative container max-w-large">
                <h4 className="text-3xl xl:text-4xl text-eno-secondary text-center mb-10 md:mb-12 2xl:mb-16">Conheça alguns dos nossos {type === 'Insumos' ? 'produtos' : 'equipamentos'}:</h4>
                <Swiper
                    slidesPerView={3}
                    slidesPerGroup={3}
                    spaceBetween={50}
                    navigation={{
                        prevEl: prevButtonRef.current,
                        nextEl: nextButtonRef.current,
                    }}
                    pagination={{ clickable: true }}
                    autoplay={{ delay: 10000, stopOnLastSlide: true }}
                    breakpoints={{
                        0: { slidesPerView: 1.3, slidesPerGroup: 1, spaceBetween: 20 },
                        500: { slidesPerView: 1.3, slidesPerGroup: 1, spaceBetween: 20 },
                        768: { slidesPerView: 2.4, slidesPerGroup: 2, spaceBetween: 30 },
                        1024: { slidesPerView: 3, slidesPerGroup: 3, spaceBetween: 30 },
                        1480: { slidesPerView: 3, slidesPerGroup: 3, spaceBetween: 50 },
                    }}
                    modules={[Navigation, Pagination, Autoplay]}
                    onBeforeInit={(swiper) => {
                        swiperRef.current = swiper;
                        swiper.params.navigation.prevEl = prevButtonRef.current;
                        swiper.params.navigation.nextEl = nextButtonRef.current;
                    }}
                    className="!pb-20 !overflow-visible [&_.swiper-pagination]:!w-auto [&_.swiper-pagination]:!z-[11] max-md:[&_.swiper-pagination]:!hidden [&_.swiper-pagination]:!bottom-[1.2rem] [&_.swiper-pagination]:flex [&_.swiper-pagination]:justify-center [&_.swiper-pagination]:gap-2 [&_.swiper-pagination]:!left-10 [&_.swiper-pagination]:!right-10 [&_.swiper-pagination-bullet]:!w-3 [&_.swiper-pagination-bullet]:!h-3 [&_.swiper-pagination-bullet]:!bg-transparent [&_.swiper-pagination-bullet]:!ring-inset [&_.swiper-pagination-bullet]:!ring-2 [&_.swiper-pagination-bullet]:!ring-neutral-700 [&_.swiper-pagination-bullet.swiper-pagination-bullet-active]:!bg-eno-primary [&_.swiper-pagination-bullet.swiper-pagination-bullet-active]:!ring-eno-primary"
                >
                    {items.map((item, index) => (
                        <SwiperSlide key={index} className="!h-auto">
                            <div className="group flex flex-col items-center shadow-2xl shadow-neutral-300 border border-neutral-200 transition-all hover:shadow-xl hover:shadow-neutral-200 py-8 h-full">
                                <img
                                    className="w-full max-w-sm p-4"
                                    src={item.imagem}
                                />

                                <h3 className="text-2xl text-neutral-900 text-center mt-auto mb-8 xl:mb-4 2xl:mb-8 px-4">{item.subcategoria ? item.nome + ' - ' + item.subcategoria : item.nome}</h3>

                                <Link href={route(`Enologia.${type}.index`, {produto: item.slug})} className="bg-eno-primary text-white text-xl px-6 py-1.5 transition-all hover:bg-eno-secondary">Conheça</Link>
                            </div>
                        </SwiperSlide>
                    ))}

                    <div className="max-md:hidden absolute z-[12] bottom-3 left-[40%] right-[40%] z-10">
                        <div className="flex justify-between">
                            <span
                                ref={prevButtonRef}
                                className="custom-prev-arrow relative w-7 h-7 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:left-2 before:w-4 before:h-4 before:border-t-2 before:border-l-2 before:-rotate-45"
                                
                            ></span>

                            <span
                                ref={nextButtonRef}
                                className="custom-prev-arrow relative w-7 h-7 cursor-pointer transition-all ease-out duration-200 hover:opacity-80 before:content-[''] before:absolute before:top-1.5 before:right-2 before:w-4 before:h-4 before:border-b-2 before:border-r-2 before:-rotate-45"
                            ></span>
                        </div>
                    </div>
                </Swiper>
            </div>
        </section>
    );
};