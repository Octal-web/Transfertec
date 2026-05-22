<?php

namespace App\Http\Controllers\Enologia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\Acontecimento;

class InstitucionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $acontecimentos = Acontecimento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'acontecimentosIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($acontecimento) {
                return [
                    'id' => $acontecimento->id,
                    'ano' => $acontecimento->ano,
                    'imagem' => asset('eno/content/timeline/thumbs/' . $acontecimento->imagem),
                    'titulo' => $acontecimento->acontecimentosIdiomas->isNotEmpty() ? $acontecimento->acontecimentosIdiomas[0]->titulo : null,
                    'texto' => $acontecimento->acontecimentosIdiomas->isNotEmpty() ? $acontecimento->acontecimentosIdiomas[0]->texto : null,
                ];
            });

        return Inertia::render('Enologia/Institucional', [
            'acontecimentos' => $acontecimentos
        ]);
    }
};