import React, { useState, useEffect, useRef } from 'react';
import { usePage, Link, Head } from '@inertiajs/react';

import Lenis from '@studio-freight/lenis';

import { CookieModal } from '@/Components/Engenharia/CookieModal';
import { MenuItem } from '@/Components/Engenharia/MenuItem';

const EngenhariaLayout = ({ children }) => {
    const { controller, action, pagina, conteudo, produtosMenu, postsMenu, notifyCookie, rejectCookie } = usePage().props;
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [openSubMenuId, setOpenSubMenuId] = useState(null);
    const [trackingEnabled, setTrackingEnabled] = useState(false);    
    const lenisRef = useRef(null);

    useEffect(() => {
        lenisRef.current = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            smooth: true,
            smoothTouch: false,
        });

        function raf(time) {
            lenisRef.current.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);

        return () => {
            lenisRef.current.destroy();
        };
    }, []);

    const toggleMenu = () => {
        setIsMenuOpen(!isMenuOpen);
    };

    const toggleSubMenu = (itemId) => {
        setOpenSubMenuId(prev => prev === itemId ? null : itemId);
    };

    const acceptCookies = () => {
        setTrackingEnabled(true);
    };

    // useEffect(() => {
    //     const timer = setTimeout(() => {
    //         if (notifyCookie || trackingEnabled) {
    //             const script = document.createElement('script');
    //             script.innerHTML = '
    //                 (function(w,d,s,l,i){
    //                     w[l]=w[l]||[];
    //                     w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
    //                     var f=d.getElementsByTagName(s)[0],
    //                         j=d.createElement(s),
    //                         dl=l!='dataLayer'?'&l='+l:'';
    //                     j.async=true;
    //                     j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
    //                     f.parentNode.insertBefore(j,f);
    //                 })(window,document,'script','dataLayer','GTM-NPCZ3MTX');
    //             ';
    //             document.head.appendChild(script);

    //             const noscript = document.createElement('noscript');
    //             noscript.innerHTML = '
    //                 <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NPCZ3MTX" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    //             ';
    //             document.body.appendChild(noscript);
    //         }
    //     }, 100);
    // }, [notifyCookie, trackingEnabled]);

    const menuItems = [
        { id: 1, name: "Home", route: "Engenharia.Home.index", controller: "Home", external: false },
        { id: 2, name: "Sobre", route: "Engenharia.Institucional.index", controller: "Institucional", external: false },
        { id: 3, name: "Setores de Atuação", route: "Engenharia.Produtos.index", controller: "Produtos", external: false, submenu: produtosMenu},
        { id: 4, name: "Serviços", route: "Engenharia.Servicos.index", controller: "Servicos", external: false },
        { id: 5, name: "News", route: "Engenharia.News.index", controller: "News", external: false, submenu: postsMenu},
        { id: 6, name: "Contato", route: "Engenharia.Contato.index", controller: "Contato", external: false },
    ];

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
            <header
                className={
                    `header top-0 left-0 right-0 bg-[length:100%_auto] shadow-sm md:shadow-none z-[2] max-sm:bg-cover ${
                        isMenuOpen ? 'fixed' : 'absolute'
                    }`
                }
                style={{
                    backgroundImage: 'url(/eng/site/img/header-bg.jpg)',
                    animation: 'moveHeaderBg 50s infinite alternate ease-out',
                }}
            >
                <style>
                    {`
                        @keyframes moveHeaderBg {
                            0% {
                                background-position: 0% 0%;
                            }
                            100% {
                                background-position: 0% 100%;
                            }
                        }
                    `}
                </style>
                <div className={`fixed inset-0 bg-black md:hidden duration-300 ease-out ${isMenuOpen ? 'opacity-50' : 'opacity-0 h-0'}`} onClick={() => {setIsMenuOpen(false)}}></div>
                <div className="container max-w-large">
                    <div className="flex items-center justify-between">
                        <div className="relative z-[1] flex items-center justify-between w-full my-3.5 md:my-5 2xl:my-8">
                            <h1 className="flex items-center">
                                <Link href={route('Engenharia.Home.index')} className="flex items-center">
                                    <img src={`/eng/site/img/logo.svg`} alt="Logo" className="block max-sm:max-w-40 max-w-80" />
                                </Link>
                            </h1>

                            <div className={`fixed md:relative bg-eng-tertiary max-md:bg-opacity-90 max-md:backdrop-blur md:bg-transparent right-0 top-0 ${!isMenuOpen && 'max-md:translate-x-full'} ${openSubMenuId ? 'w-11/12 max-md:!duration-200' : ' w-5/6' } md:left-auto md:top-auto flex flex-col md:flex-row md:items-center md:justify-end h-full md:h-auto md:my-0.5 2xl:my-1.5 transition-all ease-out duration-500`}>
                                <nav className="relative">
                                    <ul className="flex flex-col md:flex-row md:items-center md:justify-center gap-5 md:gap-2 xl:gap-10 relative max-md:pl-10 max-md:top-[40%]">
                                        {menuItems.map((item, index) => (
                                            <MenuItem key={index} item={item} controller={controller} isSubMenuOpen={openSubMenuId === item.id} onToggleSubMenu={() => toggleSubMenu(item.id)} />
                                        ))}

                                        {/* <li className="ml-4 xl:ml-15 hidden">
                                            <Link
                                                href={route('Engenharia.Orcamentos.index')}
                                                className="flex items-center px-4 py-2.5 gap-2 rounded-lg bg-white font-secondary text-eng-secondary max-sm:text-xl 2xl:text-xl font-bold transition-all hover:scale-105 hover:shadow"
                                            >
                                                <img src={`/eng/site/img/budget-icon.png`} alt="Logo" className="block" />
                                                Solicitar um orçamento
                                            </Link>

                                        </li> */}
                                    </ul>
                                </nav>
                            </div>

                            <button className="md:hidden relative z-[2]" onClick={toggleMenu}>
                                <div className="flex items-center">
                                    <div className="relative w-7 h-[21px]">
                                        <div
                                            className={`absolute top-0 bg-white h-[3px] w-7 transition-all duration-300 ${isMenuOpen ? 'rotate-45 !top-[10px]' : ''}`}
                                            style={{
                                                transitionDelay: isMenuOpen ? '0ms, 400ms' : '0ms',
                                                transitionProperty: 'top, transform'
                                            }}
                                        ></div>
                                        <div
                                            className={`absolute top-[9px] bg-white h-[3px] w-7 transition-all duration-300 ${isMenuOpen ? 'scale-x-0 !top-[10px]' : ''}`}
                                            style={{
                                                transitionDelay: isMenuOpen ? '0ms, 400ms' : '0ms',
                                                transitionProperty: 'top, transform'
                                            }}
                                        ></div>
                                        <div
                                            className={`absolute bottom-0 bg-white h-[3px] w-7 transition-all duration-300 ${isMenuOpen ? '-rotate-45 bottom-[8px]' : ''}`}
                                            style={{
                                                transitionDelay: isMenuOpen ? '0ms, 400ms' : '0ms',
                                                transitionProperty: 'bottom, transform'
                                            }}
                                        ></div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main className="overflow-hidden pt-[95px] sm:pt-[109px] md:pt-[121px] 2xl:pt-[145px]">
                {children}
            </main>

            <footer
                className="py-8 2xl:py-12 max-sm:bg-cover"
                style={{
                    backgroundImage: 'url(/eng/site/img/footer-bg.jpg)',
                    animation: 'ease-out 100s infinite alternate moveHeaderBg',
                }}
            >
                <div className="container max-w-large">
                    <div className="flex max-sm:items-center max-sm:flex-col justify-between items-start sm:gap-10 pt-10">
                        <img src={`/eng/site/img/logo.svg`} alt="Logo" className="" />

                        <div className="w-5/6 md:w-2/3">
                            <nav className="mt-10 mb-7 space-y-6 sm:space-y-8">
                                <ul className="flex max-sm:flex-wrap max-sm:justify-evenly sm:justify-end max-sm:gap-y-4 gap-8 md:gap-16">
                                    <li>
                                        <Link href={route('Engenharia.Home.index')} className="font-secondary text-white text-md font-medium transition-all opacity-70 hover:opacity-100">Home</Link>
                                    </li>

                                    <li>
                                        <Link href={route('Engenharia.Cases.index')} className="font-secondary text-white text-md font-medium transition-all opacity-70 hover:opacity-100">Cases</Link>
                                    </li>

                                    <li>
                                        <Link href={route('Engenharia.Institucional.index')} className="font-secondary text-white text-md font-medium transition-all opacity-70 hover:opacity-100">Sobre</Link>
                                    </li>

                                    <li>
                                        <Link href={route('Engenharia.Produtos.index')} className="font-secondary text-white text-md font-medium transition-all opacity-70 hover:opacity-100">Setores de Atuação</Link>
                                    </li>

                                    <li>
                                        <Link href={route('Engenharia.Servicos.index')} className="font-secondary text-white text-md font-medium transition-all opacity-70 hover:opacity-100">Serviços</Link>
                                    </li>

                                    <li>
                                        <Link href={route('Engenharia.News.index')} className="font-secondary text-white text-md font-medium transition-all opacity-70 hover:opacity-100">News</Link>
                                    </li>

                                    <li>
                                        <Link href={route('Engenharia.Contato.index')} className="font-secondary text-white text-md font-medium transition-all opacity-70 hover:opacity-100">Contato</Link>
                                    </li>
                                </ul>

                                <ul className="flex justify-center sm:justify-end gap-16">
                                    <li>
                                        <Link href={route('Engenharia.Politicas.privacidade')} className="block mb-5 text-white text-xs text-opacity-65 transition-all hover:text-opacity-100">Política de Privacidade</Link>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <div className="xl:grid xl:grid-cols-12 gap-6 items-end">
                        <div className="sm:grid sm:grid-cols-2 col-span-7">
                                <div className="max-sm:mb-4">
                                    <h5 className="font-secondary text-white font-bold text-opacity-65">Endereço:</h5>
                                    <ul className="mx-auto space-y-1">
                                        <li>
                                            <p className="text-white text-sm text-opacity-65 max-sm:[&_br]:hidden">
                                                Rua Buarque de Macedo, 2755<br /> Edifício Rubi Central Towers, Torre 2, Sala 310<br /> Bairro Centro, Garibaldi - RS,<br />CEP: 95720-000
                                            </p>
                                        </li>
                                    </ul>
                                </div>

                                <div className="space-y-4">
                                    <div>
                                        <h5 className="font-secondary text-white font-bold text-opacity-65">E-mail:</h5>
                                        <ul className="mx-auto space-y-1">
                                            <li>
                                                <a href="mailto:engenharia@transfertec.com.br" className="flex gap-2 items-center text-white text-sm text-opacity-65 transition-all hover:text-opacity-100" target="_blank" rel="noopener noreferrer">
                                                    <img src={`/eng/site/img/email-icon.png`} alt="Email icon" className="block" />
                                                    engenharia@transfertec.com.br
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div>
                                        <h5 className="font-secondary text-white font-bold text-opacity-65">Telefone:</h5>
                                        <ul className="mx-auto space-y-1">
                                            <li>
                                                <a href="tel:+5554999544154" className="flex gap-2 items-center text-white text-sm text-opacity-65 transition-all hover:text-opacity-100" target="_blank" rel="noopener noreferrer">
                                                    <img src={`/eng/site/img/phone-icon.png`} alt="Phone icon" className="block" />
                                                    (54) 99954-4154
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                        </div>
                        <div className="col-span-5 flex flex-col md:flex-row justify-between max-md:mt-10 items-center sm:items-end md:h-20 py-4 md:py-0">
                            <span className="text-white text-xs sm:text-sm opacity-70 mb-5 md:mb-0">
                                © {new Date().getFullYear()} Transfertec | Todos os direitos reservados.
                            </span>

                            <div className="flex items-end gap-4">
                                <span className="text-white text-xs sm:text-sm opacity-70">Desenvolvido por: </span>
                                <img src={`/eng/site/img/8poroito-logo.png`} className="opacity-50" />
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

            {!notifyCookie || !rejectCookie ? (
                <CookieModal acceptCookies={acceptCookies} visible={notifyCookie ? false : true} />
            ) : null}
        </>
    );
};

export default EngenhariaLayout;