<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Engenharia\Membro;
use App\Models\Engenharia\Certificacao;

class InstitucionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $membros = Membro::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'membrosIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($membro) {
                return [
                    'id' => $membro->id,
                    'visivel' => $membro->visivel,
                    'imagem' => asset('eng/content/members/' . $membro->imagem),
                    'nome' => $membro->membrosIdiomas->isNotEmpty() ? $membro->membrosIdiomas[0]->nome : null,
                ];
            });

        $certificacoes = Certificacao::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'certificacoesIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($certificacao) {
                return [
                    'id' => $certificacao->id,
                    'visivel' => $certificacao->visivel,
                    'nome' => $certificacao->certificacoesIdiomas->isNotEmpty() ? $certificacao->certificacoesIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Engenharia/Manager/Institucional/index', [
            'membros' => $membros,
            'certificacoes' => $certificacoes
        ]);
    }
};