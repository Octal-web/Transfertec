import { Reveal } from './Reveal';

export const HomeSectors = ({ sectors, content }) => {
    if (!sectors) return null;

    return (
        <section className="pt-30 2xl:pt-36">
            <div className="container max-w-large relative z-[1]">
                <div className="text-center mb-12 sm:mb-16 2xl:mb-18">
                    <h3 className="text-4xl sm:text-5xl text-white max-w-small mx-auto mb-2">{content.titulo}</h3>
                    <h5 className="text-lg text-white max-w-small mx-auto">{content.subtitulo}</h5>
                </div>

                <div className="grid sm:grid-cols-3 xl:grid-cols-5 gap-4 2xl:gap-8">
                    {sectors.map((sector, index) => (
                        <Reveal key={index} delay={index * 0.4} direction="bottom">
                            <div className="group relative flex flex-col items-start h-full bg-white rounded-lg px-6 2xl:px-8 py-10 shadow-md transition-all hover:bg-eng-tertiary shadow">
                                <img src={sector.icone} className="mb-4 transition-all group-hover:invert group-hover:brightness-0 group-hover:grayscale" />
                                <h4 className="text-eng-primary text-xl font-bold mb-2 transition-all group-hover:text-eng-secondary">{sector.nome}</h4>
                                <p className="text-eng-tertiary text-sm font-light transition-all group-hover:text-white">{sector.descricao}</p>
                            </div>
                        </Reveal>
                    ))}
                </div>
            </div>
        </section>
    );
};