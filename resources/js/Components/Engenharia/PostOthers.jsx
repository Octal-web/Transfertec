import { Link } from '@inertiajs/react';

export const PostOthers = ({ posts }) => {
    if (!posts.length) return null;

    return (
        <section className="relative pt-16 before:absolute before:left-1/2 before:w-screen before:-translate-x-1/2 before:top-0 before:h-1/2 before:bg-eng-tertiary">
            <div className="container max-w-large">
                <div className="relative flex md:gap-10 items-center justify-between mb-12">
                    <h3 className="text-3xl lg:text-[40px] text-white font-bold leading-none">Veja também</h3>
                    <Link href={route('Engenharia.News.index')} className="text-white md:hidden max-sm:text-sm font-bold underline">Ver tudo</Link>
                    <Link href={route('Engenharia.News.index')} className="text-white hidden md:block max-sm:text-sm font-bold underline">Ver todas as notícias</Link>
                </div>

                <div className="grid sm:grid-cols-2 md:grid-cols-3 gap-3 xl:gap-x-10 xl:gap-y-16 mb-15 sm:mb-30">
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
            </div>
        </section>
    );
};