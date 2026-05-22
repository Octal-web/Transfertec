<?php

namespace App\Http\Controllers\Engenharia;

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
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'membrosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
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
                    'imagem' => asset('eng/content/members/' . $membro->imagem),
                    'nome' => $membro->membrosIdiomas->isNotEmpty() ? $membro->membrosIdiomas[0]->nome : null,
                    'cargo' => $membro->membrosIdiomas->isNotEmpty() ? $membro->membrosIdiomas[0]->cargo : null,
                    'descricao' => $membro->membrosIdiomas->isNotEmpty() ? $membro->membrosIdiomas[0]->descricao : null,
                ];
            });

        $certificacoes = Certificacao::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'certificacoesIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
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
                    'logo' => asset('eng/content/certifications/thumbs/' . $certificacao->logo),
                    'nome' => $certificacao->certificacoesIdiomas->isNotEmpty() ? $certificacao->certificacoesIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Engenharia/Institucional', [
            'membros' => $membros,
            'certificacoes' => $certificacoes
        ]);
    }
};