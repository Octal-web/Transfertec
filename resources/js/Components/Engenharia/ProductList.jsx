import React, { useEffect } from 'react';

export const ProductList = ({ products }) => {
    const columns = Math.min(products.length, 5);

    const colClasses = {
        1: 'grid-cols-1',
        2: 'grid-cols-2',
        3: 'grid-cols-3',
        4: 'grid-cols-4',
        5: 'grid-cols-5',
    };

    const handleClick = (slug) => {
        const el = document.getElementById(slug);

        if (el) {
          const y = el.getBoundingClientRect().top + window.pageYOffset;
          window.scrollTo({ top: y, behavior: 'smooth' });

          window.history.pushState(null, '', `#${slug}`);
        }
    };

    return (
        <section className="bg-eng-tertiary py-16 xl:py-20">
            <div className="container max-w-large">
                <div className={`grid max-sm:auto-rows-[1fr] max-sm:!grid-cols-2 gap-6 ${colClasses[columns]}`}>
                    {products.map((product, index) => (
                        <button key={index} onClick={() => handleClick(product.slug)} className="bg-eng-primary w-full leading-tight p-3 md:p-4 rounded-lg text-white text-lg text-center transition-all hover:bg-eng-secondary">
                            {product.nome}
                        </button>
                    ))}
                </div>
            </div>
        </section>
    );
};