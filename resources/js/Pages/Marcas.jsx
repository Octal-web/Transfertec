import React, { useState, useEffect } from 'react';
import { usePage, Head } from '@inertiajs/react';

import { BrandItem } from '@/Components/BrandItem';

const Page = () => {
    const { idioma, pagina } = usePage().props;

    const [loading, setLoading] = useState(true);

    const brands = [
        {
            name: 'Transfertec Enologia',
            logo: '/eno/site/img/logo-white.png',
            description: 'Enologia',
            link: route('Enologia.Home.index'),
            color: {
                bg: 'bg-eno-secondary',
                text: 'text-eno-secondary',
                hover: 'hover:bg-eno-primary',
            },
            video: '/site/video/eno-video.mp4',
            social: [
                { name: 'instagram', link: 'https://www.instagram.com/transfertec.enologia' },
                { name: 'facebook', link: 'https://www.facebook.com/transfertec.enologia' }
            ]
        },
        {
            name: 'Transfertec Engenharia',
            logo: '/eng/site/img/logo-white.png',
            description: 'Outros Segmentos',
            link: route('Engenharia.Home.index'),
            color: {
                bg: 'bg-eng-secondary',
                text: 'text-eng-secondary',
                hover: 'hover:bg-eng-secondary',
            },
            video: '/site/video/eng-video.mp4',
            social: [
                { name: 'instagram', link: 'https://www.instagram.com/transfertec.engenharia_/' },
                { name: 'facebook', link: 'https://www.facebook.com/profile.php?id=61562953547369' }
            ]
        }
    ];
    
    useEffect(() => {
        const handleLoad = () => setLoading(false);
        
        if (document.readyState === 'complete') {
            setLoading(false);
        } else {
            window.addEventListener('load', handleLoad);
        }

        return () => window.removeEventListener('load', handleLoad);
    }, []);

    return (
        <>
            <Head>
                <title>{pagina.titulo}</title>
                <meta name="description" content={pagina.descricao} />

                <meta name="twitter:card" content="summary"/>

                <meta property="og:url" content={window.location.pathname} />
                <meta property="og:type" content="website"/>
                <meta property="og:title" content={pagina.tituloCompartilhamento} />
                <meta property="og:description" content={pagina.descricaoCompartilhamento} />
                <meta property="og:image" content={pagina.imagem.endereco} />
                <meta property="og:image:type" content={pagina.imagem.tipo} />
                <meta property="og:image:width" content={pagina.imagem.largura} />
                <meta property="og:image:height" content={pagina.imagem.altura} />

                <meta name="robots" content="index, follow"/>

                <link rel="icon" href={`/eng/favicon.ico`} type="image/x-icon" />
            </Head>

            <section className="relative">
                <div className="w-screen md:h-screen grid md:grid-cols-2">
                    {brands.map((brand, index) => (
                        <BrandItem brand={brand} key={index} />
                    ))}
                </div>

                {loading && (
                    <div className="absolute inset-0 bg-white bg-opacity-60">
                        <div className="h-full flex items-center justify-center">
                            <div className="h-16 w-16 animate-spin rounded-full border-4 border-solid border-eng-primary border-t-transparent"></div>
                        </div>
                    </div>
                )}
            </section>
        </>
    );
};

export default Page;
