<?php

namespace App\Http\Controllers\Enologia;

use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\Processo;

class AutomacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $processos = Processo::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'processosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'imagens' => function ($q) {
                    $q->where([
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($processo) {
                return [
                    'id' => $processo->id,
                    'nome' => $processo->processosIdiomas->isNotEmpty() ? $processo->processosIdiomas[0]->nome : null,
                    'utilidade' => $processo->processosIdiomas->isNotEmpty() ? $processo->processosIdiomas[0]->utilidade : null,
                    'descricao' => $processo->processosIdiomas->isNotEmpty() ? $processo->processosIdiomas[0]->descricao : null,
                    'detalhes' => $processo->processosIdiomas->isNotEmpty() ? $processo->processosIdiomas[0]->detalhes : null,
                    'layout' => $processo->layout ?? 1,
                    'slug' => $processo->slug,
                    'imagens' => $processo->imagens->map(function ($imagem) {
                        return [
                            'id' => $imagem->id,
                            'imagem' => asset('eno/content/process/gallery/s/' . $imagem->imagem),
                        ];
                    }),
                ];
            });

        return Inertia::render('Enologia/Automacao', [
            'processos' => $processos
        ]);
    }
};