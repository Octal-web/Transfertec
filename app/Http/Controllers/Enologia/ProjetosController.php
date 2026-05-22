<?php

namespace App\Http\Controllers\Enologia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\Imagem;
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

        $imagensGaleria = Imagem::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'controladora' => 'Projetos',
                'acao' => 'index'
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->mapToGroups(function($imagem) {
                return [
                    $imagem->conteudo_id => [
                        'id' => $imagem->id,
                        'imagem' => asset('eno/content/carousel/' . $imagem->imagem),
                    ]
                ];
            });

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
                    'imagem' => asset('eno/content/steps/thumbs/' . $etapa->imagem),
                    'nome' => $etapa->etapasIdiomas->isNotEmpty() ? $etapa->etapasIdiomas[0]->nome : null,
                    'descricao' => $etapa->etapasIdiomas->isNotEmpty() ? $etapa->etapasIdiomas[0]->descricao : null
                ];
            });

        return Inertia::render('Enologia/Projetos', [
            'imagensGaleria' => $imagensGaleria,
            'etapas' => $etapas
        ]);
    }
};