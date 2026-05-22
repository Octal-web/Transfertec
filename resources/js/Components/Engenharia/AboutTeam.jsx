import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const AboutTeam = ({ content, members }) => {
    if (!members) return;

    return (
        <section className="pt-24 bg-eng-primary">
            <div className="container max-w-large">
                <Reveal direction="bottom">
                    <h2 className="text-4xl sm:text-5xl text-white text-center mb-5 sm:mb-10">{content.titulo}</h2>
                    <p className="font-secondary max-w-2xl text-white text-center mx-auto mb-16 sm:mb-20">{content.texto}</p>
                </Reveal>

                <div className="relative grid sm:grid-cols-2 xl:grid-cols-3 shadow-md before:absolute before:bottom-0 before:h-1/2 before:left-1/2 before:w-screen before:-translate-x-1/2 before:bg-eng-tertiary">
                    {members.map((member, index) => {
                        const isReversedMobile = index % 2 !== 0;
                        const isReversedTablet = Math.floor(index / 2) % 2 !== 0;
                        const isReversedDesktop = Math.floor(index / 3) % 2 !== 0;
                        
                        return (
                            <Reveal
                                key={index}
                                className={`
                                    group flex flex-row
                                    ${isReversedMobile ? 'max-sm:flex-row-reverse' : ''}
                                    ${isReversedTablet ? 'sm:max-xl:flex-row-reverse' : ''}
                                    ${isReversedDesktop ? 'xl:flex-row-reverse' : ''}
                                `}
                            >
                                <img src={member.imagem} alt={member.nome} className="w-1/2 h-full object-cover" />
                                
                                <div className="bg-white w-1/2 px-6 2xl:px-10 py-4 flex flex-col justify-center transition-all group-hover:bg-eng-secondary">
                                    <h3 className="text-eng-primary text-2xl sm:text-3xl font-semibold mb-2 sm:mb-4 transition-all group-hover:text-white">{member.nome}</h3>
                                    <h4 className="text-eng-tertiary text-sm tracking-tight mb-4">{member.cargo}</h4>
                                    <div className="font-secondary text-sm font-light max-sm:tracking-tighter max-2xl:leading-tight transition-all group-hover:text-white">{member.descricao}</div>
                                </div>
                            </Reveal>
                        );
                    })}
                </div>
            </div>

            <div className="pt-30 bg-eng-tertiary" />
        </section>
    );
};