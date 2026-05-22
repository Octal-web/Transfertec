import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const HomeCases = ({ casesClients, content }) => {
    return (
        <section className="pt-30 2xl:pt-36">
            <div className="container max-w-large relative z-[1]">
                <div className="text-center mb-12 sm:mb-16 2xl:mb-18">
                    <h3 className="text-4xl sm:text-5xl text-white">{content.titulo}</h3>
                </div>

                <div className="grid grid-cols-1 xl:grid-cols-2 shadow-md">
                    {casesClients.map((caseClient, index) => {
                        const isDesktopReversed = Math.floor(index / 2) % 2 !== 0;
                        const isMobileReversed = index % 2 !== 0;

                        return (
                            <Reveal
                                key={index}
                                className={`
                                    group flex 
                                    ${isMobileReversed ? 'flex-row-reverse' : ''}
                                    ${isDesktopReversed ? 'xl:flex-row-reverse' : 'xl:flex-row'}
                                `}
                            >
                                <img
                                    src={caseClient.imagem}
                                    alt={caseClient.nome}
                                    className="w-1/2 min-h-full h-50 sm:h-64 xl:h-full object-cover"
                                />

                                <div className="bg-white w-1/2 px-5 py-4 sm:py-10 sm:px-10 2xl:px-12 sm:py-12 2xl:py-15 flex flex-col justify-evenly transition-all group-hover:bg-eng-tertiary">
                                    <h3 className="text-eng-primary text-xl sm:text-2xl max-sm:leading-none max-2xl:!leading-tight max-sm:tracking-tighter max-w-60 mb-3 sm:mb-4 transition-all group-hover:text-white">
                                        {caseClient.nome}
                                    </h3>
                                    {caseClient.empresa && (
                                        <h4 className="text-eng-tertiary 2xl:text-xl tracking-tight mb-4 transition-all group-hover:text-eng-secondary">
                                            {caseClient.empresa}
                                        </h4>
                                    )}
                                    <div className="font-secondary max-sm:text-xs max-sm:leading-tight max-sm:mb-2 max-2xl:leading-tight font-light transition-all group-hover:text-white">
                                        {caseClient.descricao}
                                    </div>

                                    <Link href={route('Engenharia.Cases.caseCliente', {slug: caseClient.slug})} className="flex gap-2 items-center font-secondary text-eng-secondary text-xs font-bold uppercase transition-all py-2 hover:gap-3">
                                        Ver mais

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="30.758"
                                            height="15.142"
                                            viewBox="0 0 30.758 15.142"
                                            fill="none"
                                            className="stroke-eng-secondary"
                                        >
                                            <path
                                                d="M1 7.57h28.758"
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                            />
                                            <path
                                                d="M23.623 1.408l5.135 6.163-5.135 6.163"
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                        />
                                          </svg>
                                    </Link>
                                </div>
                            </Reveal>
                        );
                    })}
                </div>

                <div className="pt-10">
                    <Link href={route('Engenharia.Cases.index')} className="relative w-fit block mx-auto font-secondary text-eng-primary underline transition-all hover:opacity-70">Ver todos os cases</Link>
                </div>
            </div>
        </section>
    );
};