import React, { useEffect, useRef } from 'react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from './Reveal';

export const ContactBanner = () => {
    const aboutbgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(aboutbgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: aboutbgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <section className="relative pt-10 md:pt-16">
            <div className="relative container max-w-large">
                <h2 className="text-neutral-900 text-5xl 2xl:text-[70px] font-bold mb-10 xl:mb-14">Fale <span className="font-light">conosco</span></h2>
                
                <div className="bg-eno-secondary p-8 md:px-14 md:py-10 2xl:p-14 absolute max-md:-bottom-36 max-md:translate-y-full md:top-0 max-md:left-[10%] right-[10%] md:right-[5%] md:w-1/2 max-w-md">
                    <div className="space-y-2 md:space-y-4 2xl:space-y-6">
                        <div>
                            <h5 className="font-secondary 2xl:text-lg text-white font-bold text-opacity-70 md:mb-2">Endereço:</h5>
                            <ul className="mx-auto space-y-1 2xl:mb-8">
                                <li className="max-md:!leading-none">
                                    <p className="text-white text-sm max-sm:leading-tight">
                                        Rua Buarque de Macedo, 2755<br /> Edifício Rubi Central Towers, Torre 2, Sala 310<br /> Bairro Centro, Garibaldi - RS,<br />CEP: 95720-000
                                    </p>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h5 className="font-secondary 2xl:text-lg text-white font-bold text-opacity-70 md:mb-2">E-mail:</h5>
                            <ul className="space-y-1">
                                <li>
                                    <a href="mailto:enologia@transfertec.com.br" className="flex gap-2 items-center text-white text-sm transition-all hover:text-opacity-80" target="_blank" rel="noopener noreferrer">
                                        <img src={`/eno/site/img/email-icon.png`} alt="Email icon" className="block" />
                                        enologia@transfertec.com.br
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h5 className="font-secondary 2xl:text-lg text-white font-bold text-opacity-70 md:mb-2">Telefone:</h5>
                            <ul className="space-y-1">
                                <li>
                                    <a href="tel:+5554996340246" className="flex gap-2 items-center text-white text-sm transition-all hover:text-opacity-80" target="_blank" rel="noopener noreferrer">
                                        <img src={`/eno/site/img/phone-icon.png`} alt="Phone icon" className="block" />
                                        (54) 99634-0246
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <ul className="flex items-center gap-4 mt-6 md:mt-8 2xl:mt-10">
                                <li>
                                    <a href="https://facebook.com/profile.php?id=61562953547369" className="flex items-center justify-center w-9 md:w-11 h-9 md:h-11 border border-white rounded-full transition-all hover:opacity-80" target="_blank" rel="noopener noreferrer">
                                        <img src={`/eno/site/img/facebook-icon.png`} alt="Phone icon" className="block" />
                                    </a>
                                </li>

                                <li>
                                    <a href="https://instagram.com/transfertec.engenharia_/" className="flex items-center justify-center w-9 md:w-11 h-9 md:h-11 border border-white rounded-full transition-all hover:opacity-80" target="_blank" rel="noopener noreferrer">
                                        <img src={`/eno/site/img/instagram-icon.png`} alt="Phone icon" className="block" />
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div
                ref={aboutbgRef}
                className="aspect-[2/1] xl:aspect-[96/23] max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] bg-[60%] xl:bg-[length:100%] max-md:mb-50"
                style={{
                    backgroundImage: `url(/eno/site/img/contact-bg.jpg)`,
                }}
            />
        </section>
    );
};