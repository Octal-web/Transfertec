import { Reveal } from './Reveal';

export const AboutText = ({ content }) => {
    return (
        <section className="bg-neutral-100 pt-16 md:pt-20">
            <div className="container max-w-large">
                <div className="grid md:grid-cols-2 xl:items-end gap-16 pb-28">
                    <Reveal direction="left">
                        <h1 className="text-4xl sm:text-5xl text-eno-secondary font-light mb-10">{content.titulo}</h1>
                        <div className="font-secondary max-2xl:text-sm text-neutral-900 max-w-xl text-justify" dangerouslySetInnerHTML={{ __html: content.texto }} />
                    </Reveal>

                    <Reveal direction="right">
                        <img src={content.imagem} />
                    </Reveal>
                </div>
            </div>
        </section>
    );
};