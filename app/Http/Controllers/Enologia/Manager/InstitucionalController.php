<?php

namespace App\Http\Controllers\Enologia\Manager;

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
                'excluido' => NULL
            ])
            ->with([
                'acontecimentosIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
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
                    'visivel' => $acontecimento->visivel,
                    'nome' => $acontecimento->ano,
                    'imagem' => asset('eno/content/timeline/thumbs/' . $acontecimento->imagem),
                ];
            });

        return Inertia::render('Enologia/Manager/Institucional/index', [
            'acontecimentos' => $acontecimentos
        ]);
    }
};