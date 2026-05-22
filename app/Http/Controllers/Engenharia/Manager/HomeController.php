<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Engenharia\Slide;
use App\Models\Engenharia\Setor;
use App\Models\Engenharia\Depoimento;
use App\Models\Engenharia\Cliente;

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

        $setores = Setor::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'setoresIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($setor) {
                return [
                    'id' => $setor->id,
                    'visivel' => $setor->visivel,
                    'nome' => $setor->setoresIdiomas->isNotEmpty() ? $setor->setoresIdiomas[0]->nome : null,
                ];
            });

        $depoimentos = Depoimento::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'depoimentosIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($depoimento) {
                return [
                    'id' => $depoimento->id,
                    'visivel' => $depoimento->visivel,
                    'imagem' => asset('eng/content/testimonies/thumbs/' . $depoimento->imagem),
                    'nome' => $depoimento->depoimentosIdiomas->isNotEmpty() ? $depoimento->depoimentosIdiomas[0]->nome : null,
                ];
            });

        $clientes = Cliente::query()
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
                    'logo' => asset('eng/content/clients/thumbs/' . $cliente->logo),
                    'nome' => $cliente->nome,
                ];
            });

        return Inertia::render('Engenharia/Manager/Home/index', [
            'slides' => $slides,
            'setores' => $setores,
            'depoimentos' => $depoimentos,
            'clientes' => $clientes
        ]);
    }
};