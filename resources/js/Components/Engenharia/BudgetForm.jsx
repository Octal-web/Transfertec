import React, { useState, useEffect, useRef } from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';

import { InputMask } from '@react-input/mask';
import Select from 'react-select';

import AnimatedCheckMark from './AnimatedCheckMark';

export const BudgetForm = () => {
    const { message } = usePage().props;

    const [loading, setLoading] = useState(false);
    const [isSuccessful, setIsSuccessful] = useState(false);
    const [selectedExpectIndex, setSelectedExpectIndex] = useState(null);

    const { data, setData, post, processing, errors, clearErrors } = useForm({
        nome: '',
        email: '',
        cnpj: '',
        telefone: '',
        cargo: '',
        forma_contato: '',
        mensagem: '',
        politica: false
    });

    const expectOptions = [
        { value: "nesta_semana", label: "Nesta semana" },
        { value: "neste_mes", label: "Neste mês" },
        { value: "proximo_mes", label: "Próximo mês" },
        { value: "proximo_semestre", label: "Próximo semestre"},
        { value: "proximo_ano", label: "Próximo ano" },
    ];

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setData(prevData => ({
            ...prevData,
            [name]: type === 'checkbox' ? checked : value
        }));
        clearErrors(name);
    };

    const handleSelectChange = (selectedOption) => {
        setData(prevData => ({
            ...prevData,
            forma_contato: selectedOption ? selectedOption.value : ''
        }));
        clearErrors('forma_contato');
    };

    const handleExpectChange = (e) => {
        const index = Number(e.target.value);
        setSelectedExpectIndex(index);
        setData(prevData => ({
            ...prevData,
            expectativa_compra: expectOptions[index].value
        }));
        clearErrors('expectativa_compra');
    };

    const selectedExpectValue = selectedExpectIndex !== null ? expectOptions[selectedExpectIndex].value : "";

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        post(route('Engenharia.Orcamentos.enviar'), {
            preserveScroll: true,
            onSuccess: (page) => {
                setLoading(false);
            },
            onError: () => {
                setLoading(false);
            }
        });
    };

    useEffect(() => {  
        if (message && message.type === 'success') {
            setIsSuccessful(true);

            setTimeout(() => {
                setData({
                    nome: '',
                    email: '',
                    cnpj: '',
                    telefone: '',
                    cargo: '',
                    forma_contato: '',
                    mensagem: '',
                    politica: false
                });

                setIsSuccessful(false);
            }, 3000);
        }
    }, [message]);

    const contactOptions = [
        { value: 'whatsapp', label: 'WhatsApp' },
        { value: 'email', label: 'E-mail' },
        { value: 'presencial', label: 'Presencial' },
        { value: 'videoconferencia', label: 'Videoconferência' }
    ];

    return (
        <section className="relative py-14 md:py-20">
            <div className="container max-w-medium">
                <h1 className="text-5xl 2xl:text-[78px] text-eng-primary font-light leading-none mb-3 xm:mb-6">Solicite um <b>orçamento</b></h1>
                <h4 className="font-secondary mb-8 2xl:mb-12">Preencha os campos abaixo e entraremos em contato com você.</h4>

                <form
                    onSubmit={handleSubmit}
                >
                    <div className="mb-8 2xl:mb-12 flex gap-3 md:gap-6 flex-col lg:flex-row hidden">
                        <div className="w-full">
                            <label className="block text-eng-primary text-lg font-bold mb-4">Expectativa de compra</label>

                            <div className="relative w-full">
                                <div className="absolute top-7 left-0 w-full h-0.5 bg-slate-300 rounded transform" />

                                <div className="absolute top-7 left-4 md:left-20 right-4 md:right-20 flex justify-between transform -translate-y-1/2">
                                    {expectOptions.map((_, index) => (
                                        <div
                                            key={index}
                                            className={`w-9 h-9 rounded-full border-2 border-slate-300 ring-4 ring-inset ring-white transition-colors duration-200 ${
                                                selectedExpectIndex === index
                                                  ? 'bg-eng-primary'
                                                  : 'bg-white'
                                            }`}
                                        />
                                    ))}
                                </div>

                                <input
                                    type="range"
                                    min={0}
                                    max={expectOptions.length - 1}
                                    step={1}
                                    value={selectedExpectIndex ?? ""}
                                    onChange={handleExpectChange}
                                    className="relative w-full appearance-none bg-transparent px-[1.7rem] md:px-[4.9rem] pt-2 opacity-0 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-10 [&::-webkit-slider-thumb]:w-10"
                                />

                                <div className="flex justify-between text-sm font-bold text-neutral-700 px-0.5 md:px-[2rem]">
                                    {expectOptions.map((opt, index) => (
                                        <div key={index} className="w-[17%] md:w-[12%] text-center max-sm:tracking-tighter">{opt.label}</div>
                                    ))}
                                </div>

                                <input type="hidden" name="expectativa_compra" value={selectedExpectValue} />
                            </div>
                            {errors.expectativa_compra && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.expectativa_compra}</p>}
                        </div>
                    </div>

                    <div className="mb-3 2xl:mb-5 flex gap-3 md:gap-6 flex-col md:flex-row">
                        <div className="w-full md:w-1/2">
                            <input type="text" name="nome" value={data.nome} onChange={handleChange} placeholder="Seu Nome" maxLength="60" className="w-full h-12 px-4 border-1 border-eng-tertiary rounded focus:outline-none focus:ring-0 focus:border-eng-primary focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-70"  />
                            {errors.nome && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.nome}</p>}
                        </div>

                        <div className="w-full md:w-1/2">
                            <input type="text" name="email" value={data.email} onChange={handleChange} placeholder="Seu E-mail" maxLength="60" className="w-full h-12 px-4 border-1 border-eng-tertiary rounded focus:outline-none focus:ring-0 focus:border-eng-primary focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-70" />
                            {errors.email && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.email}</p>}
                        </div>
                    </div>

                    <div className="mb-3 2xl:mb-5 flex gap-3 md:gap-6 flex-col md:flex-row">
                        <div className="w-full md:w-1/2">
                            <InputMask type="text" name="cnpj" mask="__.___.___/____-__" replacement={{ _: /\d/ }} value={data.cnpj} onChange={handleChange} placeholder="Seu CNPJ" className="w-full h-12 px-4 border-1 border-eng-tertiary rounded focus:outline-none focus:ring-0 focus:border-eng-primary focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-70" />
                            {errors.cnpj && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.cnpj}</p>}
                        </div>

                        <div className="w-full md:w-1/2">
                            <InputMask type="text" name="telefone" mask="(__) _____-____" replacement={{ _: /\d/ }} value={data.telefone} onChange={handleChange} placeholder="Seu telefone: (DDD) + Numero" className="w-full h-12 px-4 border-1 border-eng-tertiary rounded focus:outline-none focus:ring-0 focus:border-eng-primary focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-70" />
                            {errors.telefone && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.telefone}</p>}
                        </div>
                    </div>
                    <div className="mb-3 2xl:mb-5 flex gap-3 md:gap-6 flex-col md:flex-row">
                        <div className="w-full md:w-1/2">
                            <input type="text" name="cargo" value={data.cargo} onChange={handleChange} placeholder="Seu Cargo" maxLength="60" className="w-full h-12 px-4 border-1 border-eng-tertiary rounded focus:outline-none focus:ring-0 focus:border-eng-primary focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-70" />
                            {errors.cargo && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.cargo}</p>}
                        </div>

                        <div className="w-full md:w-1/2">
                            <Select
                                options={contactOptions}
                                onChange={handleSelectChange}
                                placeholder="Como quer receber seu contato?"
                                isSearchable={false}
                                classNamePrefix="form-eng-select"
                            />
                            {errors.forma_contato && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.forma_contato}</p>}
                        </div>
                    </div>
                    
                    <div className="mb-3 2xl:mb-5 flex gap-3 md:gap-6 flex-col lg:flex-row">
                        <div className="w-full">
                            <textarea name="mensagem" value={data.mensagem} onChange={handleChange} placeholder="Escreva aqui..." className="block w-full h-48 resize-none px-4 border-1 border-eng-tertiary rounded focus:outline-none focus:ring-0 focus:border-eng-primary focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-70" />
                            {errors.mensagem && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.mensagem}</p>}
                        </div>
                    </div>

                    <div className="mb-3 2xl:mb-5 flex gap-3 md:gap-6 flex-col lg:flex-row">
                        <div className="my-2 w-full">
                            <label className="flex items-center">
                                <label className="relative flex">
                                    <input type="checkbox" name="politica" checked={data.politica} onChange={handleChange} className="peer w-5 h-5 rounded-md bg-white border-neutral-600 checked:bg-white checked:border-neutral-600 checked:bg-[length:0_0] checked:hover:bg-white checked:hover:border-neutral-600 checked:focus:bg-white checked:focus:border-neutral-600 !outline-0 !ring-0 !ring-offset-0" />
                                    <span className="peer-checked:content-[''] peer-checked:absolute peer-checked:inset-1 rounded peer-checked:bg-eng-primary" />
                                </label>

                                <span className="text-neutral-600 ml-2">Li e concordo com a <Link href={route('Engenharia.Politicas.privacidade')} className="underline" target="_blank" rel="noopener noreferrer">Política de Privacidade</Link>.</span>
                            </label>
                            {errors.politica && <p className="text-xs text-red-500 border-b border-b-red-500 px-2 py-1 mt-1">{errors.politica}</p>}
                        </div>

                        <button
                            type="submit"
                            className="relative px-7 py-3 w-fit lg:text-xl 2xl:text-2xl bg-eng-primary font-secondary text-white text-center font-semibold rounded-xl whitespace-nowrap transition-all hover:bg-eng-secondary"
                        >
                            {!loading ? (
                                'Enviar mensagem'
                            ) : (
                                <>
                                    <div role="status" className="absolute inset-0 flex justify-center items-center">
                                        <svg aria-hidden="true" className="w-8 h-8 text-gray-200 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                        </svg>
                                        <span className="sr-only">Loading...</span>
                                    </div>
                                    <span className="opacity-0">Enviar mensagem</span>
                                </>
                            )}
                        </button>
                    </div>
                </form>
            </div>

            {isSuccessful && (
                <div className={`absolute inset-0 flex flex-col items-center justify-center bg-white pointer-events-none animate-fade-in-down`}>
                    <AnimatedCheckMark />

                    <h2 className="text-eng-primary text-4xl text-center font-semibold mt-4 mb-2">Sucesso!</h2>
                    <h4 className="font-secondary text-eng-tertiary text-2xl text-center">Sua mensagem foi enviada.</h4>
                </div>
            )}
        </section>
    );
};