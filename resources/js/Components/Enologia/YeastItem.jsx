import React, { useState, useEffect } from 'react';

export const YeastItem = ({ item }) => {
    const [isOpen, setIsOpen] = useState(false);
    const [isClosing, setIsClosing] = useState(false);

    const handleOpen = () => {
        setIsOpen(true);
    };

    const handleClose = () => {
        setIsClosing(true);
        setTimeout(() => {
            setIsOpen(false);
            setIsClosing(false);
        }, 200);
    };

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        const produtoSlug = params.get('produto');
        if (produtoSlug && produtoSlug === item.slug) {
            setIsOpen(true);
        }
    }, [item.slug]);

    useEffect(() => {
        const handleEscKey = (event) => {
            if (event.key === 'Escape' && isOpen) {
                handleClose();
            }
        };

        if (isOpen) {
            document.addEventListener('keydown', handleEscKey);
        }

        return () => {
            document.removeEventListener('keydown', handleEscKey);
        };
    }, [isOpen]);

    return (
        <>
            <button onClick={handleOpen} className="md:p-4 transition-all hover:bg-black bg-opacity-5 md:hover:shadow-lg hover:bg-opacity-5 flex flex-col items-center h-full">
                <img src={item.imagem} className="mb-2" />
                <h3 className="text-xl sm:text-2xl text-eno-secondary text-center font-semibold mb-1">{item.nome}</h3>
                <h4 className="font-secondary max-sm:text-xs font-light mb-2">{item.utilidade}</h4>
            </button>

            {isOpen && (
                <div className="fixed top-0 left-0 w-full h-full flex items-center justify-center transition-opacity duration-300 z-50">
                    <div onClick={handleClose} className={`absolute inset-0 bg-black transition-all ${isClosing ? 'bg-opacity-0' : 'bg-opacity-70'}`} />
                    <div className="container max-w-large">
                        <div className={`bg-white rounded shadow-lg relative z-60 ${isClosing ? 'animate-fade-out-down' : 'animate-fade-in-down [animation-duration:_0.2s]'}`}>
                            <button
                                className="absolute top-2 md:top-4 right-2 md:right-4 text-white bg-eno-secondary w-7 md:w-10 h-7 md:h-10 text-xl transition-all z-[1] hover:bg-opacity-90"
                                onClick={handleClose}
                            >
                                ✕
                            </button>
                            <div className="flex max-md:flex-col-reverse border border-eno-secondary max-h-[90vh]">
                                <div className="md:w-[55%] md:border-r border-eno-secondary overflow-y-auto" data-lenis-prevent>
                                    <div className="md:min-h-[50%] p-6 bg-eno-secondary flex flex-col justify-center">
                                        <div className="mx-[4%] justify-center max-w-2xl">
                                            <h3 className="text-2xl md:text-3xl text-white mb-3 md:mb-4">{item.nome}</h3>
                                            <div className="text-xs sm:text-sm text-white" dangerouslySetInnerHTML={{ __html: item.descricao }} />
                                        </div>
                                    </div>

                                    <div className="md:min-h-[50%] p-6 flex flex-col justify-center">
                                        <div className="mx-[4%] justify-center max-w-2xl">
                                            <h4 className="text-xl text-eno-secondary font-bold mb-2">Características Técnicas:</h4>
                                            <div className="text-xs sm:text-sm [&_li]:before:content-['→'] [&_li]:before:pr-1" dangerouslySetInnerHTML={{ __html: item.detalhes }} />
                                        </div>
                                    </div>
                                </div>

                                <div className="relative md:w-[45%] max-md:border-b max-md:border-eno-secondary">
                                    <img src={item.imagem} className={`max-h-full${item.ficha_tecnica || item.ficha_de_seguranca ? ' scale-90' : ''} origin-top max-md:pb-20`} />

                                    <div className="absolute left-10 right-10 bottom-4 md:bottom-8 gap-2 md:gap-10 flex flex-col sm:flex-row justify-center">
                                            {item.ficha_tecnica && (
                                                <a 
                                                    href={route('Enologia.Insumos.download', {tipo: 'ficha-tecnica', id: item.id})} 
                                                    className="flex items-center justify-center gap-2 sm:gap-3 p-1.5 sm:p-2 w-full sm:w-1/2 max-w-none sm:max-w-[14em] border-2 border-eno-secondary rounded bg-eno-secondary fill-white text-white font-semibold transition-all hover:bg-white hover:text-neutral-900 hover:fill-neutral-900 text-sm sm:text-base"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 -3.47 52.68 52.68" className="sm:w-5 sm:h-5">
                                                        <g id="Group_50" data-name="Group 50" transform="translate(-449.318 -569.91)">
                                                            <g id="Group_49" data-name="Group 49">
                                                                <path id="Path_31" data-name="Path 31" d="M500.5,615.653h-49.68a1.5,1.5,0,0,1-1.5-1.5v-30.4a1.5,1.5,0,0,1,1.5-1.5h10.466a1.5,1.5,0,1,1,0,3h-8.966v27.4H499v-27.4h-7.929a1.5,1.5,0,0,1,0-3H500.5a1.5,1.5,0,0,1,1.5,1.5v30.4A1.5,1.5,0,0,1,500.5,615.653Z" />
                                                            </g>
                                                            <path id="Path_32" data-name="Path 32" d="M486.464,593.6a1.5,1.5,0,0,0-2.121,0l-7.185,7.185V571.41a1.5,1.5,0,0,0-3,0v29.379l-7.185-7.186a1.5,1.5,0,1,0-2.121,2.121l9.745,9.745a1.488,1.488,0,0,0,.491.327l.023.007a1.452,1.452,0,0,0,1.094,0l.023-.007a1.492,1.492,0,0,0,.492-.327l9.744-9.745A1.5,1.5,0,0,0,486.464,593.6Z" />
                                                        </g>
                                                    </svg>
                                                    Ficha Técnica
                                                </a>
                                            )}

                                            {item.ficha_de_seguranca && (
                                                <a 
                                                    href={route('Enologia.Insumos.download', {tipo: 'ficha-seguranca', id: item.id})} 
                                                    className="flex items-center justify-center gap-2 sm:gap-3 p-1.5 sm:p-2 w-full sm:w-1/2 max-w-none sm:max-w-[14em] border-2 border-eno-secondary rounded bg-eno-secondary fill-white text-white font-semibold transition-all hover:bg-white hover:text-neutral-900 hover:fill-neutral-900 text-sm sm:text-base"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 -3.47 52.68 52.68" className="sm:w-5 sm:h-5">
                                                        <g id="Group_50" data-name="Group 50" transform="translate(-449.318 -569.91)">
                                                            <g id="Group_49" data-name="Group 49">
                                                                <path id="Path_31" data-name="Path 31" d="M500.5,615.653h-49.68a1.5,1.5,0,0,1-1.5-1.5v-30.4a1.5,1.5,0,0,1,1.5-1.5h10.466a1.5,1.5,0,1,1,0,3h-8.966v27.4H499v-27.4h-7.929a1.5,1.5,0,0,1,0-3H500.5a1.5,1.5,0,0,1,1.5,1.5v30.4A1.5,1.5,0,0,1,500.5,615.653Z" />
                                                            </g>
                                                            <path id="Path_32" data-name="Path 32" d="M486.464,593.6a1.5,1.5,0,0,0-2.121,0l-7.185,7.185V571.41a1.5,1.5,0,0,0-3,0v29.379l-7.185-7.186a1.5,1.5,0,1,0-2.121,2.121l9.745,9.745a1.488,1.488,0,0,0,.491.327l.023.007a1.452,1.452,0,0,0,1.094,0l.023-.007a1.492,1.492,0,0,0,.492-.327l9.744-9.745A1.5,1.5,0,0,0,486.464,593.6Z" />
                                                        </g>
                                                    </svg>
                                                    Ficha de Segurança
                                                </a>
                                            )}
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};