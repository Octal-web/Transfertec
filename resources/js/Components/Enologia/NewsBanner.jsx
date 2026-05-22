import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';
import { PostsCategoryFilter } from './PostsCategoryFilter';

export const NewsBanner = ({ content, categories, onCategoryChange, selectedCategory, setSelectedCategory }) => {
    const newsBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(newsBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: newsBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <>
            <section 
                ref={newsBgRef}
                className="relative w-full aspect-[3/1] md:aspect-[6/1] xl:aspect-[768/100] max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] bg-[60%] xl:bg-[length:100%]"
                style={{
                    backgroundImage: `url(${content.imagem})`,
                }}
            >
                <div className="absolute inset-0 bg-neutral-900 mix-blend-overlay opacity-50" />
            </section>

            <section className="pt-16 pb-10">
                <div className="container max-w-large">
                    <h1 className="text-neutral-900 text-4xl md:text-5xl xl:text-6xl font-light mb-8">
                        {(() => {
                            const [primeira, ...resto] = content.titulo.split(' ');
                                return (
                                    <>
                                        {primeira}{" "}
                                        <span className="text-eno-tertiary font-bold">{resto.join(' ')}</span>
                                    </>
                                );
                        })()}
                    </h1>

                    <div className="flex items-center justify-between">
                        <h4 className="font-secondary text-2xl text-eno-tertiary">{content.subtitulo}</h4>

                        <PostsCategoryFilter 
                            categories={categories}
                            selectedCategory={selectedCategory}
                            setSelectedCategory={setSelectedCategory}
                            onCategoryChange={onCategoryChange}
                        />
                    </div>
                </div>
            </section>
        </>
    );
};