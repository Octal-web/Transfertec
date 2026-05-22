<?php

namespace App\Http\Controllers\Enologia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\Contato;

use App\Http\Requests\Enologia\PostContactRequest;

use Illuminate\Support\Facades\Mail;

class ContatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return Inertia::render('Enologia/Contato');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function enviar(PostContactRequest $request) {
        if($request->post()){
            $contato = new Contato;

            $contato->nome = $request->nome;
            $contato->email = $request->email;
            $contato->telefone = $request->telefone;
            $contato->mensagem = $request->mensagem;

            $response = $contato->save();

            if ($response) {
                $data = [
                    'nome'     => $request->nome,
                    'email'    => $request->email,
                    'telefone' => $request->telefone,
                    'mensagem' => $request->mensagem,
                ];

                Mail::send('emails.eno.contact', $data, function($message)use($data) {
                    $message->from('financeiro@transfertec.com.br', 'Transfertec Enologia')
                            ->to('engenharia@transfertec.com.br')
                            ->bcc('rafael@8poroito.com.br')
                            ->subject('Um novo contato foi enviado pelo site');
                });

                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Contato enviado com sucesso!.']);
            }
        }

        return Inertia::location(route('Enologia.Contato.index'));
    }
};