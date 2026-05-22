<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\Slide;
use App\Models\Enologia\Solucao;
use App\Models\Enologia\Parceiro;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $slides = Slide::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'slidesIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($slide) {
                return [
                    'id' => $slide->id,
                    'visivel' => $slide->visivel,
                    'titulo' => (($slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->titulo : null) . ' - ' . ($slide->tipo == 'imagem' ? 'Imagem' : 'Vídeo')),
                ];
            });

        $solucoes = Solucao::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'solucoesIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($solucao) {
                return [
                    'id' => $solucao->id,
                    'visivel' => $solucao->visivel,
                    'imagem' => asset('eno/content/solutions/thumbs/' . $solucao->icone),
                    'titulo' => $solucao->solucoesIdiomas->isNotEmpty() ? $solucao->solucoesIdiomas[0]->titulo : null,
                ];
            });

        $parceiros = Parceiro::query()
            ->where([
                'excluido' => NULL
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($cliente) {
                return [
                    'id' => $cliente->id,
                    'visivel' => $cliente->visivel,
                    'logo' => asset('eno/content/partners/thumbs/' . $cliente->logo),
                    'nome' => $cliente->nome,
                ];
            });

        return Inertia::render('Enologia/Manager/Home/index', [
            'slides' => $slides,
            'solucoes' => $solucoes,
            'parceiros' => $parceiros
        ]);
    }
};