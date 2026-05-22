import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';
import CasesPagination from './CasesPagination';

export const CasesList = ({ casesClientes, loading, links, totalPages, onPageChange }) => {
    if (!casesClientes.length) return null;

    return (
        <section className="relative pt-30 2xl:pt-36 pb-10">
            <div
                className="absolute inset-0 top-0 bg-cover bg-fixed"
                style={{
                    backgroundImage: `url(/eng/site/img/cases-bg.jpg)`,
                }}
            />
            <div className="container max-w-large">
                <div className={`grid sm:grid-cols-2 md:grid-cols-3 gap-3 xl:gap-x-8 xl:gap-y-16 -mt-50 min-[1422px]:-mt-[26em] mb-15 sm:mb-30${loading ? ' opacity-50' : ''}`}>
                    {casesClientes.map((item, index) => (
                        <Reveal direction="top" key={index} className="group relative flex flex-col shadow-md shadow">
                            <Link href={route('Engenharia.Cases.caseCliente', {slug: item.slug})} className="">
                                <img src={item.imagem} className="block aspect-[5/4] object-cover" />
                            </Link>
                            <div className="bg-white h-full px-5 py-4 sm:py-4 sm:px-10 2xl:px-12 sm:py-6 2xl:py-8 flex flex-col transition-all group-hover:bg-eng-tertiary">
                                <h3 className="text-eng-primary text-xl sm:text-2xl max-sm:leading-none max-2xl:!leading-tight max-sm:tracking-tighter mb-3 sm:mb-4 transition-all group-hover:text-white">
                                    {item.nome}
                                </h3>
                                {item.empresa && (
                                    <h4 className="text-eng-tertiary 2xl:text-xl tracking-tight mb-4 transition-all group-hover:text-eng-secondary">
                                        {item.empresa}
                                    </h4>
                                )}
                                <div className="font-secondary max-sm:text-xs max-sm:leading-tight mb-2 md:mb-4 max-2xl:leading-tight font-light transition-all group-hover:text-white">
                                    {item.descricao}
                                </div>

                                <Link href={route('Engenharia.Cases.caseCliente', {slug: item.slug})} className="flex gap-2 font-secondary text-eng-secondary text-xs font-bold uppercase transition-all mt-auto py-2 hover:gap-3">
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
                    ))}
                </div>
            </div>

            <CasesPagination 
                links={links} 
                totalPages={totalPages}
                onPageChange={onPageChange}
            />
        </section>
    );
};