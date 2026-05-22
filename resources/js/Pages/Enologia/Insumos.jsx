import React, { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import DefaultLayout from '@/Layouts/EnologiaLayout';

import { YeastVideo } from '@/Components/Enologia/YeastVideo';
import { YeastHeader } from '@/Components/Enologia/YeastHeader';
import { YeastItems } from '@/Components/Enologia/YeastItems';

const Page = () => {
    const { insumosCategorias, conteudos } = usePage().props;
    const [selectedCategory, setSelectedCategory] = useState(insumosCategorias[0]);
    const [selectedSubcategory, setSelectedSubcategory] = useState(insumosCategorias[0].subcategorias[0]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        const produtoSlug = params.get('produto');

        if (produtoSlug) {
            for (const categoria of insumosCategorias) {
                for (const sub of categoria.subcategorias) {
                    const produto = sub.insumos.find(p => p.slug === produtoSlug);
                    if (produto) {
                        setSelectedCategory(categoria);
                        setSelectedSubcategory(sub);
                        break;
                    }
                }
            }
        }
        
        setLoading(false);
    }, [insumosCategorias]);

    return (
        <DefaultLayout>
            <section className="relative min-h-[70vh] bg-neutral-900 py-20 xl:py-10 2xl:py-20 flex flex-col justify-center">
                <YeastVideo />
                
                <YeastHeader
                    content={conteudos[0]}
                    categories={insumosCategorias}
                    selectedCategory={selectedCategory}
                    setSelectedCategory={setSelectedCategory}
                    selectedSubcategory={selectedSubcategory}
                    setSelectedSubcategory={setSelectedSubcategory}
                    loading={loading}
                />
            </section>

            <YeastItems items={selectedCategory.subcategorias} currentSub={selectedSubcategory} showSubName={selectedCategory.subcategorias.length > 1 && selectedCategory.subcategorias[0]?.nome !== 'Todos'} />
        </DefaultLayout>
    );
};

export default Page;
''