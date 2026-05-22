import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const AboutCertifications = ({ content, certifications, partners }) => {
    return (
        <section className="relative max-sm:py-20 py-30">
            <div className="hidden xl:block absolute top-0 bottom-0 right-0 w-1/4 bg-cover" style={{ backgroundImage: 'url(/eng/site/img/certif-bg.jpg)'}} />
            <div className="container max-w-large">
                <div className="grid md:grid-cols-2">
                    <Reveal direction="left">
                        <h1 className="text-4xl sm:text-5xl xl:text-6xl mb-8 max-w-sm">
                            {content.titulo.split(' ').map((word, index) => (
                                <span key={index} className={index === 0 ? 'text-eng-primary font-bold' : 'text-eng-tertiary font-light'}>
                                    {word + ' '}
                                </span>
                            ))}
                        </h1>
                        <div className="font-secondary text-slate-700 max-w-lg pb-10 sm:pb-24" dangerouslySetInnerHTML={{ __html: content.texto }} />
                    </Reveal>

                    <div className="flex gap-6">
                        {certifications.map((item, index) => (
                            <Reveal key={index} direction="right" delay={index * 0.5}>
                                <img src={item.logo} alt={item.nome} className="bg-white border-2 border-eng-primary rounded-lg" />
                            </Reveal>
                        ))}
                    </div>
                </div>

                <div className="grid md:grid-cols-2 max-md:gap-10 max-md:mt-10 max-w-4xl 2xl:max-w-6xl">
                    <Reveal direction="left" delay="0.4" className="relative pl-14 sm:pl-20 before:absolute before:top-3 before:left-0 before:w-10 sm:before:w-14 before:h-0.5 before:bg-eng-tertiary pr-8">
                        <h3 className="text-eng-tertiary text-xl font-bold mb-4">Certificações:</h3>
                        <ul className="text-slate-700 list-disc list-inside [&_::marker]:content-['•'] [&_::marker]:tracking-[10px]">
                            {certifications.map((item, index) => (
                                <li key={index}>{item.nome}</li>
                            ))}
                        </ul>
                    </Reveal>

                    <Reveal direction="left" delay="0.8" className="relative pl-14 sm:pl-20 before:absolute before:top-3 before:left-0 before:w-10 sm:before:w-14 before:h-0.5 before:bg-eng-tertiary md:-ml-10">
                        <h3 className="text-eng-tertiary text-xl font-bold mb-4">Parcerias:</h3>
                        <ul className="text-slate-700 list-disc list-inside [&_li>p]:contents [&_::marker]:content-['•'] [&_::marker]:tracking-[10px]" dangerouslySetInnerHTML={{ __html: partners.texto }} />
                    </Reveal>
                </div>
            </div>
        </section>
    );
};