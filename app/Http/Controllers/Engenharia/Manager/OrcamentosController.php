<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Engenharia\Orcamento;

use Illuminate\Http\Request;
use Inertia\Inertia;

use Carbon\Carbon;

class OrcamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $orcamentos = Orcamento::query()
            ->where([
                'excluido' => NULL
            ])
            ->orderBy('criado', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($orcamento) {
                return [
                    'id' => $orcamento->id,
                    'nome' => $orcamento->nome,
                    'data' => $orcamento->criado->format('d/m/Y'),
                ];
            });

        return Inertia::render('Engenharia/Manager/Orcamentos/index', [
            'orcamentos' => $orcamentos
        ]);
    }

    public function visualizar($id) {
        if (!$id) {
            return Inertia::location(route('Engenharia.Manager.Orcamentos.index'));
        }
        
        $orcamento = Orcamento::query()
            ->where([
                'excluido' => NULL,
            ])
            ->first();

        if(!$orcamento) {
            return Inertia::location(route('Engenharia.Manager.Orcamentos.index'));
        }

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

        $orcamentoData = [
            'id' => $orcamento->id,
            // 'expectativa_compra' => $expectOptions[$orcamento->expectativa_compra],
            'nome' => $orcamento->nome,
            'email' => $orcamento->email,
            'cnpj' => $orcamento->cnpj,
            'telefone' => $orcamento->telefone,
            'cargo' => $orcamento->cargo,
            'forma_contato' => $contactOptions[$orcamento->forma_contato],
            'mensagem' => $orcamento->mensagem,
            'data' => $orcamento->criado->format('d/m/Y H:i'),
        ];

        return Inertia::render('Engenharia/Manager/Orcamentos/visualizar', [
            'orcamento' => $orcamentoData
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

            $exclusao = Orcamento::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $id
                ])
                ->update([
                    'excluido' => Carbon::now()
                ]);

            if ($exclusao == true) {
                return redirect(route('Engenharia.Manager.Orcamentos.index'))->with('message', ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']);
            } else {
                return redirect(route('Engenharia.Manager.Orcamentos.index'))->with('message', ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
            }
        }
    }
}