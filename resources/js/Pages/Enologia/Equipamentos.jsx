import React, { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import DefaultLayout from '@/Layouts/EnologiaLayout';

import { EquipmentVideo } from '@/Components/Enologia/EquipmentVideo';
import { EquipmentHeader } from '@/Components/Enologia/EquipmentHeader';
import { EquipmentItems } from '@/Components/Enologia/EquipmentItems';

const Page = () => {
    const { equipamentosCategorias, conteudos } = usePage().props;
    const [selectedCategory, setSelectedCategory] = useState(equipamentosCategorias[0]);
    const [selectedSubcategory, setSelectedSubcategory] = useState(equipamentosCategorias[0].subcategorias[0]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        const produtoSlug = params.get('produto');

        if (produtoSlug) {
            for (const categoria of equipamentosCategorias) {
                for (const sub of categoria.subcategorias) {
                    const produto = sub.equipamentos.find(p => p.slug === produtoSlug);
                    if (produto) {
                        setSelectedCategory(categoria);
                        setSelectedSubcategory(sub);
                        break;
                    }
                }
            }
        }
        
        setLoading(false);
    }, [equipamentosCategorias]);

    return (
        <DefaultLayout>
            <section className="relative min-h-[70vh] bg-neutral-900 py-20 xl:py-10 2xl:py-20 flex flex-col justify-center">
                <EquipmentVideo />
                
                <EquipmentHeader
                    content={conteudos[0]}
                    categories={equipamentosCategorias}
                    selectedCategory={selectedCategory}
                    setSelectedCategory={setSelectedCategory}
                    selectedSubcategory={selectedSubcategory}
                    setSelectedSubcategory={setSelectedSubcategory}
                    loading={loading}
                />
            </section>

            <EquipmentItems items={selectedCategory.subcategorias} currentSub={selectedSubcategory} showSubName={selectedCategory.subcategorias.length > 1 && selectedCategory.subcategorias[0]?.nome !== 'Todos'} />
        </DefaultLayout>
    );
};

export default Page;
''