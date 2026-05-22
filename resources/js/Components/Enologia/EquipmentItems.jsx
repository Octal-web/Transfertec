import { useState, useEffect } from 'react';

import { EquipmentItem } from './EquipmentItem';

export const EquipmentItems = ({ items, currentSub, showSubName }) => {
    if (!currentSub) return;

    const [isMobile, setIsMobile] = useState(false);

    useEffect(() => {
        const checkMobile = () => {
            setIsMobile(window.innerWidth < 768);
        };

        checkMobile();
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);

    const groupItems = (items) => {
        if (isMobile) {
            return items.map(item => ({
                type: 'single',
                item: item
            }));
        }
        
        const groups = [];
        let i = 0;

        while (i < items.length) {
            const currentItem = items[i];
            const nextItem = items[i + 1];

            const currentIsSmall = currentItem.equipamentos.length <= 2;
            const nextIsSmall = nextItem && nextItem.equipamentos.length <= 2;

            if (currentIsSmall && nextIsSmall) {
                groups.push({
                    type: 'grouped',
                    items: [currentItem, nextItem]
                });
                i += 2;
            } else {
                groups.push({
                    type: 'single',
                    item: currentItem
                });
                i += 1;
            }
        }

        return groups;
    };

    const groupedItems = groupItems(items);

    return (
        <>
            {groupedItems.map((group, groupIndex) => {
                if (group.type === 'single') {
                    const item = group.item;
                    return (
                        <section
                            key={item.id}
                            id={item.slug}
                            className={`py-20 md:pb-22 md:pt-24 ${groupIndex % 2 === 0 ? 'bg-white' : 'bg-gray-100'}`}
                        >
                            <div className="container max-w-large">
                                {showSubName && <h3 className="text-2xl sm:text-3xl text-center mb-6 md:mb-12">{item.nome}</h3>}

                                {item.equipamentos.length ? (
                                    <div className="flex flex-wrap justify-center gap-4">
                                        {item.equipamentos.map((equipamento) => (
                                            <div className="w-[calc((100%-16px)/2)] sm:w-[calc((100%-32px)/3)] md:w-[calc((100%-48px)/4)]">
                                                <EquipmentItem key={equipamento.id} item={equipamento} />
                                            </div>
                                        ))}
                                    </div>
                                ) : null}
                            </div>
                        </section>
                    );
                } else {
                    const [item1, item2] = group.items;
                    return (
                        <section
                            key={`${item1.id}-${item2.id}`}
                            className={`py-20 md:pb-20 md:pt-24 ${groupIndex % 2 === 0 ? 'bg-gray-50' : 'bg-gray-100'}`}
                        >
                            <div className="container max-w-large">
                                <div className="grid md:grid-cols-2 gap-12 md:gap-16">
                                    <div id={item1.slug}>
                                        {showSubName && !(item1.equipamentos.length === 1 && item1.nome === currentSub.nome) && (
                                            <h3 className="text-2xl sm:text-3xl text-center mb-6 md:mb-12">{item.nome}</h3>
                                        )}
                                        {item1.equipamentos.length ? (
                                            <div className="flex flex-wrap justify-center gap-4">
                                                {item1.equipamentos.map((equipamento) => (
                                                    <div className="w-[calc((100%-16px)/2)]">
                                                        <EquipmentItem key={equipamento.id} item={equipamento} />
                                                    </div>
                                                ))}
                                            </div>
                                        ) : null}
                                    </div>

                                    <div className={`relative before:absolute before:-bottom-20 before:-top-24 before:w-[calc(100vw_+_2em)] before:-left-8 ${groupIndex % 2 === 0 ? 'before:bg-gray-100' : 'before:bg-gray-50'}`} id={item2.slug}>
                                        {showSubName && !(item2.equipamentos.length === 1 && item2.nome === currentSub.nome) && (
                                            <h3 className="text-2xl sm:text-3xl text-center mb-6 md:mb-12">{item.nome}</h3>
                                        )}
                                        {item2.equipamentos.length ? (
                                            <div className="relative flex flex-wrap justify-center gap-4">
                                                {item2.equipamentos.map((equipamento) => (
                                                    <div className="w-[calc((100%-16px)/2)]">
                                                        <EquipmentItem key={equipamento.id} item={equipamento} />
                                                    </div>
                                                ))}
                                            </div>
                                        ) : null}
                                    </div>
                                </div>
                            </div>
                        </section>
                    );
                }
            })}
        </>
    );
};