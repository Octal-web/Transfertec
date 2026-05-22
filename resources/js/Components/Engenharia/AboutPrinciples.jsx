import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const AboutPrinciples = ({ content, items }) => {

    return (
        <section className="pt-24 bg-eng-tertiary">
            <div className="container max-w-large">
                {/* <Reveal direction="bottom">
                    <h2 className="text-4xl sm:text-5xl text-white text-center mb-5 sm:mb-10">{content.titulo}</h2>
                    <p className="font-secondary max-w-2xl text-white text-center mx-auto mb-16 sm:mb-20">{content.texto}</p>
                </Reveal> */}

                <div className="relative grid md:grid-cols-3 gap-6 xl:gap-10 before:absolute before:bottom-0 before:h-1/2 before:left-1/2 before:w-screen before:-translate-x-1/2 before:bg-eng-primary pb-24">
                    {items.map((item, index) => (
                        <Reveal key={index} delay={index * 0.6} className="relative bg-white rounded-lg px-6 xl:px-8 py-10 shadow-xl min-h-80">
                            <img src={item.imagem} className="block mx-auto mb-3 sm:mb-5" />

                            <h5 className="font-secondary text-slate-600 text-lg text-center font-bold mb-4">{item.titulo}</h5>

                            <p className="font-secondary text-gray-500 max-xl:text-sm text-center italic max-w-sm mx-auto">{item.texto}</p>
                        </Reveal>
                    ))}
                </div>
            </div>
        </section>
    );
};