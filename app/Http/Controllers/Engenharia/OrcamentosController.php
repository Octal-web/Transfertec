<?php

namespace App\Http\Controllers\Engenharia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Engenharia\Orcamento;

use App\Http\Requests\Engenharia\PostBudgetRequest;

use Illuminate\Support\Facades\Mail;

class OrcamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return Inertia::render('Engenharia/Orcamentos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function enviar(PostBudgetRequest $request) {
        if($request->post()){
            $orcamento = new Orcamento;

            // $orcamento->expectativa_compra = $request->expectativa_compra;
            $orcamento->nome = $request->nome;
            $orcamento->email = $request->email;
            $orcamento->cnpj = $request->cnpj;
            $orcamento->telefone = $request->telefone;
            $orcamento->cargo = $request->cargo;
            $orcamento->forma_contato = $request->forma_contato;
            $orcamento->mensagem = $request->mensagem;

            $response = $orcamento->save();

            $expectOptions = [
                'nesta_semana' => 'Nesta semana',
                'neste_mes' => 'Neste mês',
                'proximo_mes' => 'Próximo mês',
                'proximo_semestre' => 'Próximo semestre',
                'proximo_ano' => 'Próximo ano',
            ];

            $contactOptions = [
                'whatsapp' => 'WhatsApp',
                'email' => 'E-mail',
                'presencial' => 'Presencial',
                'videoconferencia' => 'Videoconferência',
            ];

            if ($response) {
                $data = [
                    // 'expectativa_compra' => $expectOptions[$request->expectativa_compra],
                    'nome'               => $request->nome,
                    'email'              => $request->email,
                    'cnpj'               => $request->cnpj,
                    'telefone'           => $request->telefone,
                    'cargo'              => $request->cargo,
                    'forma_contato'      => $contactOptions[$request->forma_contato],
                    'mensagem'           => $request->mensagem,
                ];

                Mail::send('emails.eng.budget', $data, function($message)use($data) {
                    $message->from('financeiro@transfertec.com.br', 'Transfertec Engenharia')
                            ->to('egenharia@transfertec.com.br')
                            ->bcc('rafael@8poroito.com.br')
                            ->subject('Um novo orçamento foi solicitado pelo site');
                });

                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Orçamento solicitado com sucesso!.']);
            }
        }

        return Inertia::location(route('Engenharia.Orcamentos.index'));
    }
};