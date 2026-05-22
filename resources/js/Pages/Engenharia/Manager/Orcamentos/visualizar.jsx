import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faMoneyBillWaveAlt, faArrowLeft, faTrash } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminEngenhariaLayout';
import { Breadcrumb } from '@/Components/Engenharia/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Engenharia/Manager/ConfirmModal';

const Page = () => {
    // Content
    const { idioma, orcamento } = usePage().props;

    const [isModalOpen, setIsModalOpen] = useState(false);

    const breadcrumbItems = [
        { label: 'Solicite Orçamento', link: 'Engenharia.Manager.Orcamentos.index' },
    ];

    const openModal = () => {
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faMoneyBillWaveAlt} items={breadcrumbItems} current="Visualizar" idioma={idioma.codigo} />
            
            <div className="mb-6 rounded-sm border border-stroke bg-white px-5 py-5 shadow-md">
                <div className="flex items-center justify-between">
                    <h3 className="text-xl font-bold text-black">Visualizar solicitação</h3>
                </div>

                <div className="mt-10">
                    <div className="flex flex-col gap-y-2">
                        <p><b>Nome</b>: {orcamento.nome}</p>
                        <p><b>E-mail</b>: {orcamento.email}</p>
                        {/* <p><b>Expectativa de Compra</b>: {orcamento.expectativa_compra}</p> */}
                        <p><b>CNPJ</b>: {orcamento.cnpj}</p>
                        <p><b>Telefone</b>: {orcamento.telefone}</p>
                        <p><b>Cargo</b>: {orcamento.cargo}</p>
                        <p><b>Forma de contato desejada</b>: {orcamento.forma_contato}</p>
                        <p><b>Mensagem</b>: {orcamento.mensagem}</p>
                        <p><b>Data</b>: {orcamento.data }</p>
                    </div>
                </div>

                <div className="flex items-center justify-end">
                    <Link href={route('Engenharia.Manager.Orcamentos.index')} className="block relative w-fit rounded-lg border mr-3 border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200">
                        <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                        Voltar
                    </Link>

                    <button
                        onClick={() => openModal(orcamento.id)}
                        className="flex items-center w-fit rounded-lg border border-red-700 text-red-700 px-3 py-2 cursor-pointer transition-all hover:bg-red-100"
                    >   
                        <FontAwesomeIcon icon={faTrash} className="text-red-700 mr-2" />
                        Excluir
                    </button>
                </div>

                {isModalOpen && <ConfirmModal icon={faTrash} closeModal={closeModal} type="delete" confirm={route('Engenharia.Manager.Orcamentos.excluir', {id: orcamento.id})} />}
            </div>
        </AdminLayout>
    );
};

export default Page;
