<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\Etapa;

class ProjetosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $etapas = Etapa::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'etapasIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($etapa) {
                return [
                    'id' => $etapa->id,
                    'visivel' => $etapa->visivel,
                    'nome' => $etapa->etapasIdiomas->isNotEmpty() ? $etapa->etapasIdiomas[0]->nome : null,
                    'imagem' => asset('eno/content/steps/thumbs/' . $etapa->imagem),
                ];
            });

        return Inertia::render('Enologia/Manager/Projetos/index', [
            'etapas' => $etapas
        ]);
    }
};