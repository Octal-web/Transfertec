<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\Contato;

use Illuminate\Http\Request;
use Inertia\Inertia;

use Carbon\Carbon;

class ContatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $contatos = Contato::query()
            ->where([
                'excluido' => NULL
            ])
            ->orderBy('criado', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($contato) {
                return [
                    'id' => $contato->id,
                    'nome' => $contato->nome,
                    'data' => $contato->criado->format('d/m/Y'),
                ];
            });

        return Inertia::render('Enologia/Manager/Contato/index', [
            'contatos' => $contatos
        ]);
    }

    public function visualizar($id) {
        if (!$id) {
            return Inertia::location(route('Enologia.Manager.Contato.index'));
        }
        
        $contato = Contato::query()
            ->where([
                'excluido' => NULL,
            ])
            ->first();

        if(!$contato) {
            return Inertia::location(route('Enologia.Manager.Contato.index'));
        }

        $contato = [
            'id' => $contato->id,
            'nome' => $contato->nome,
            'email' => $contato->email,
            'telefone' => $contato->telefone,
            'mensagem' => $contato->mensagem,
            'data' => $contato->criado->format('d/m/Y H:i'),
        ];

        return Inertia::render('Enologia/Manager/Contato/visualizar', [
            'contato' => $contato
        ]);
    }

    /**
     * Set the specified resource as deleted.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function excluir(Request $request, $id) {
        if ($request->ajax()){
            if (!$id) {
                return $request->header('referer');
            }

            $exclusao = Contato::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $id
                ])
                ->update([
                    'excluido' => Carbon::now()
                ]);

            if ($exclusao == true) {
                return redirect(route('Enologia.Manager.Contato.index'))->with('message', ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']);
            } else {
                return redirect(route('Enologia.Manager.Contato.index'))->with('message', ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
            }
        }
    }
}