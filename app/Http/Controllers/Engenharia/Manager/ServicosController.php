<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Engenharia\Etapa;

use Illuminate\Http\Request;

use Inertia\Inertia;
class ServicosController extends Controller
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
                    'imagem' => asset('eng/content/steps/thumbs/' . $etapa->imagem),
                    'nome' => $etapa->etapasIdiomas->isNotEmpty() ? $etapa->etapasIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Engenharia/Manager/Servicos/index', [
            'etapas' => $etapas
        ]);
    }
};