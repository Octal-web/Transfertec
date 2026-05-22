import { Reveal } from './Reveal';

export const ProjectPrinciples = ({ items }) => {
    return (
        <section className="relative -mt-32 md:-mt-40 z-[1]">
            <div className="container max-w-large">
                <div className="relative grid md:grid-cols-3 gap-6 xl:gap-10 before:absolute before:bottom-0 before:h-[calc(100%_-_9.375rem)] before:left-1/2 before:w-screen before:-translate-x-1/2 before:bg-neutral-100 pb-24">
                    {items.map((item, index) => (
                        <Reveal key={index} delay={index * 0.6} className="relative bg-white px-6 xl:px-8 pt-10 pb-16 shadow-xl min-h-80">
                            <img src={item.imagem} className="block mx-auto mb-3 sm:mb-5" />

                            <h4 className="font-secondary text-neutral-900 text-2xl text-center font-bold mb-6">{item.titulo}</h4>

                            <p className="font-secondary text-gray-500 max-xl:text-sm text-center italic max-w-sm px-5 mx-auto">{item.texto}</p>
                        </Reveal>
                    ))}
                </div>
            </div>
        </section>
    );
};