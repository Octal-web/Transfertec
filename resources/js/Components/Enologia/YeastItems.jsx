import { YeastItem } from './YeastItem';

export const YeastItems = ({ items, currentSub, showSubName }) => {
    if (!currentSub) return;

    return (
        <>
            {items.map((item, index) => (
                <section
                    key={item.id}
                    id={item.slug}
                    className={`py-20 md:pb-24 md:pt-28 ${index % 2 === 0 ? 'bg-white' : 'bg-gray-100'}`}
                >
                    <div className="container max-w-large">
                        {showSubName && <h3 className="text-2xl sm:text-3xl mb-6 md:mb-12">{item.nome}</h3>}

                        {item.insumos.length ? (
                            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                {item.insumos.map((insumo) => (
                                    <YeastItem key={insumo.id} item={insumo} />
                                ))}
                            </div>
                            ) : null}
                            {item.imagem_grafico ? (
                                <img src={item.imagem_grafico} className="block mt-10 max-w-5xl mx-auto" />
                            ) : null}
                    </div>
                </section>
            ))}
        </>
    );
};