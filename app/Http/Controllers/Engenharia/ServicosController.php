<?php

namespace App\Http\Controllers\Engenharia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Engenharia\Etapa;

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
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'etapasIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($etapa) {
                return [
                    'id' => $etapa->id,
                    'imagem' => asset('eng/content/steps/thumbs/' . $etapa->imagem),
                    'nome' => $etapa->etapasIdiomas->isNotEmpty() ? $etapa->etapasIdiomas[0]->nome : null,
                    'descricao' => $etapa->etapasIdiomas->isNotEmpty() ? $etapa->etapasIdiomas[0]->descricao : null
                ];
            });

        return Inertia::render('Engenharia/Servicos', [
            'etapas' => $etapas
        ]);
    }
};