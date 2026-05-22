import { Link } from '@inertiajs/react';

export const HomePosts = ({ posts }) => {
    if (!posts.length) return null;

    return (
        <section className="pt-30 2xl:pt-36">
            <div className="container max-w-large">
                <div className="text-center mb-12 sm:mb-16 2xl:mb-20">
                    <h3 className="text-4xl sm:text-5xl text-eng-tertiary">Transfertec News</h3>
                </div>

                <div className="relative sm:grid sm:grid-cols-3 max-sm:space-y-3 gap-3 xl:gap-8 before:absolute before:left-1/2 before:w-screen before:-translate-x-1/2 before:bottom-0 before:h-1/2 before:bg-eng-primary">
                    {posts.map((item, index) => (
                        <div key={index} className="relative flex flex-col">
                            <Link href={route('Engenharia.News.post', {categoria: item.categoria_slug, slug: item.slug})} className="relative mb-4">
                                <img src={item.imagem} className="block border border-eng-primary rounded-xl transition-all hover:opacity-80" />

                                <span className="absolute top-4 right-4 py-2 px-4 bg-eng-primary font-secondary text-xs text-white font-bold rounded-xl">{item.categoria}</span>
                            </Link>
                            <div className="h-full flex flex-col">
                                <Link href={route('Engenharia.News.post', {categoria: item.categoria_slug, slug: item.slug})} className="mb-3 sm:mb-4">
                                    <h3 className="text-white text-xl sm:text-2xl max-sm:leading-none max-2xl:!leading-tight max-sm:tracking-tighter">
                                        {item.titulo}
                                    </h3>
                                </Link>

                                <div className="font-secondary text-white max-sm:text-xs max-sm:leading-tight mb-2 max-2xl:leading-tight md:mr-2 max-w-sm font-light">
                                    {item.previa}
                                </div>

                                <div>
                                    <Link href={route('Engenharia.News.post', {categoria: item.categoria_slug, slug: item.slug})} className="gap-2 font-secondary text-eng-secondary text-lg font-bold underline transition-all mt-auto py-2 hover:opacity-80">
                                        Ler agora
                                    </Link>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                <div className="relative pt-10 pb-20 sm:pb-30 before:absolute before:left-1/2 before:w-screen before:-translate-x-1/2 before:bottom-0 before:top-0 before:bg-eng-primary">
                    <Link href={route('Engenharia.News.index')} className="relative w-fit block mx-auto font-secondary text-white underline transition-all hover:opacity-70">Ver todas as news</Link>
                </div>
            </div>
        </section>
    );
};