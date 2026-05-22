import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import PostsPagination from './PostsPagination';

export const NewsList = ({ posts, loading, links, totalPages, onPageChange }) => {
    return (
        <section className="py-10">
            <div className="container max-w-large">
                {posts && posts.length ? (
                    <div className={`grid sm:grid-cols-2 md:grid-cols-3 gap-3 xl:gap-x-10 xl:gap-y-16 mb-15 sm:mb-30${loading ? ' opacity-50' : ''}`}>
                        {posts.map((item, index) => (
                            <div key={index} className="flex flex-col">
                                <Link href={route('Engenharia.News.post', {categoria: item.categoria_slug, slug: item.slug})} className="relative mb-4">
                                    <img src={item.imagem} className="block border border-eng-primary rounded-xl transition-all hover:opacity-80" />

                                    <span className="absolute top-4 right-4 py-2 px-4 bg-eng-primary font-secondary text-xs text-white font-bold rounded-xl">{item.categoria}</span>
                                </Link>
                                <div className="h-full flex flex-col">
                                    <Link href={route('Engenharia.News.post', {categoria: item.categoria_slug, slug: item.slug})} className="mb-3 sm:mb-4">
                                        <h3 className="text-eng-primary text-xl sm:text-2xl max-sm:leading-none max-2xl:!leading-tight max-sm:tracking-tighter">
                                            {item.titulo}
                                        </h3>
                                    </Link>

                                    <div className="font-secondary max-sm:text-xs max-sm:leading-tight mb-2 max-2xl:leading-tight md:mr-2 max-w-sm font-light">
                                        {item.previa}
                                    </div>

                                    <div>
                                        <Link href={route('Engenharia.News.post', {categoria: item.categoria_slug, slug: item.slug})} className="gap-2 font-secondary text-eng-primary text-lg font-bold underline transition-all mt-auto py-2 hover:opacity-80">
                                            Ler agora
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <h3 className="font-eng-secondary text-3xl text-center my-20">Não foram encontradas postagens com o filtro selecionado</h3>
                )}
            </div>

            {posts && posts.length ? (
                <PostsPagination 
                    links={links} 
                    totalPages={totalPages}
                    onPageChange={onPageChange}
                />
            ) : null}
        </section>
    );
};