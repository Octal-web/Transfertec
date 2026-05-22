import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const HomePartners = ({ partners }) => {
    if (!partners) return null;

    return (
        <section className="pt-30 mb-10">
            <div className="container max-w-large">
                <div className="text-center mb-12 sm:mb-16 2xl:mb-20">
                    <h3 className="text-4xl sm:text-5xl text-eno-secondary">Nossos Parceiros</h3>
                </div>

                <div className="flex flex-wrap justify-center gap-y-6 xl:gap-y-10 mb-12 xl:mb-16">
                    {partners.map((partner, index) => (
                        <Reveal key={index} delay={index * 0.6} className="w-1/2 sm:w-1/4 xl:w-1/5" scale={true}>
                            <a href={partner.link} target="_blank" rel="noopener noreferrer">
                                <img src={partner.logo} alt={partner.nome} className="transition-all hover:scale-110 px-3 sm:px-6 grayscale hover:grayscale-0" />
                            </a>
                        </Reveal>
                    ))}
                </div>
            </div>
        </section>
    );
};