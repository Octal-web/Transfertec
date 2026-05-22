import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const HomeClients = ({ clients }) => {
    if (!clients) return null;

    return (
        <section className="pt-30 2xl:pt-36 mb-30">
            <div className="container max-w-large">
                <div className="text-center mb-12 sm:mb-16 2xl:mb-20">
                    <h3 className="text-4xl sm:text-5xl text-eng-tertiary">Alguns de nossos clientes</h3>
                </div>

                <div className="flex flex-wrap justify-center gap-y-6 xl:gap-y-10 mb-12 xl:mb-16">
                    {clients.map((client, index) => (
                        <Reveal key={index} delay={index * 0.6} className="w-1/2 sm:w-1/4 xl:w-1/6">
                            <img src={client.logo} alt={client.nome} className="transition-all hover:scale-110 px-3 sm:px-6 grayscale hover:grayscale-0" />
                        </Reveal>
                    ))}
                </div>
            </div>
        </section>
    );
};