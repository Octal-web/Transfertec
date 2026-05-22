import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const HomeTestimonies = ({ testimonies, content }) => {
    if (!testimonies) return null;
    
    return (
        <section className="pt-30 2xl:pt-36">
            <div className="container max-w-large relative z-[1]">
                <div className="text-center mb-12 sm:mb-16 2xl:mb-20">
                    <h5 className="font-secondary text-eng-tertiary opacity-70 uppercase mb-8">Depoimentos</h5>
                    
                    <h3 className="text-eng-primary text-4xl sm:text-5xl mb-6 max-w-4xl mx-auto">{content.titulo}</h3>
                </div>

                <div className={`max-sm:space-y-4 sm:grid ${testimonies.length < 6 ? `sm:grid-cols-${testimonies.length}` : 'sm:grid-cols-3'} gap-5 xl:gap-10`}>
                    {testimonies.map((testimony, index) => (
                        <Reveal key={index} delay={index * 0.6} className="relative bg-white border border-eng-primary rounded-lg px-6 xl:px-8 py-10 shadow-xl min-h-60 sm:min-h-80">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="40"
                                height="35"
                                viewBox="0 0 40.442 34.938"
                                className="fill-eng-primary opacity-30 absolute top-10 right-10 rotate-180 max-xl:hidden"
                            >
                                <path d="M.009,25.311a9.737,9.737,0,0,0,9.843,9.627,9.629,9.629,0,1,0,0-19.254,10,10,0,0,0-3.188.526C8.879,3.785,18.785-4.228,9.6,2.367-.581,9.68,0,25.017.009,25.3.009,25.3.009,25.306.009,25.311Z" />
                                <path d="M.009,25.311a9.737,9.737,0,0,0,9.843,9.627,9.629,9.629,0,1,0,0-19.254,10,10,0,0,0-3.188.526C8.879,3.785,18.785-4.228,9.6,2.367-.581,9.68,0,25.017.009,25.3.009,25.3.009,25.306.009,25.311Z" transform="translate(20.748)" />
                            </svg>

                            <div className="flex gap-3 xl:gap-10 items-center mb-8">
                                <img src={testimony.imagem} className="w-16 xl:w-[88px] h-16 xl:h-[88px] rounded-full" />

                                <div>
                                    <h5 className="text-eng-secondary text-lg font-bold mb-1">{testimony.nome}</h5>
                                    <h6 className="text-gray-600">{testimony.empresa}</h6>
                                </div>
                            </div>

                            <p className="font-secondary text-gray-500 italic max-w-sm">{testimony.depoimento}</p>
                        </Reveal>
                    ))}
                </div>
            </div>
        </section>
    );
};