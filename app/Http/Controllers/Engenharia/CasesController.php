<?php

namespace App\Http\Controllers\Engenharia;

use App\Http\Controllers\Controller;

use Inertia\Inertia;

use App\Models\Engenharia\Pagina;
use App\Models\Engenharia\CaseCliente;

class CasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $casesClientes = CaseCliente::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'casesClientesIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'produto' => function ($q) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ]);
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(12);

        $casesClientes->getCollection()->transform(function($caseCliente) {
            return [
                'id' => $caseCliente->id,
                'imagem' => asset('eng/content/cases/thumbs/' . $caseCliente->imagem),
                'nome' => $caseCliente->casesClientesIdiomas->isNotEmpty() ? $caseCliente->casesClientesIdiomas[0]->nome : null,
                'empresa' => $caseCliente->casesClientesIdiomas->isNotEmpty() ? $caseCliente->casesClientesIdiomas[0]->empresa : null,
                'descricao' => $caseCliente->casesClientesIdiomas->isNotEmpty() ? $caseCliente->casesClientesIdiomas[0]->descricao : null,
                'slug' => $caseCliente->slug,
            ];
        });

        return Inertia::render('Engenharia/CasesClientes', [
            'casesClientes' => $casesClientes
        ]);
    }

    /**
     * Show the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function caseCliente($slug) {
        if (!$slug) {
            return Inertia::location(route('Engenharia.Home.index'));
        }
        
        $idioma = inertia()->getShared('idioma');

        $caseCliente = CaseCliente::query()
            ->where([
                'excluido' => null,
                'visivel' => true,
                'slug' => $slug
            ])
            ->with([
                'casesClientesIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'blocos' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->with([
                        'blocosIdiomas' => function ($sub) use ($idioma) {
                            $sub->whereHas('idiomas', function ($r) use ($idioma) {
                                $r->where('codigo', $idioma)
                                  ->orWhere('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC');
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
            ->first();

        if(!$caseCliente) {
            return Inertia::location(route('Engenharia.Home.index'));
        }

        $pagina = new Pagina;

        $pagina->titulo = $caseCliente->casesClientesIdiomas[0]->titulo_pagina . ' - Transfertec Engenharia';
        $pagina->descricao = $caseCliente->casesClientesIdiomas[0]->descricao_pagina . ' - Transfertec Engenharia';
        $pagina->titulo_compartilhamento = $caseCliente->casesClientesIdiomas[0]->titulo_pagina . ' - Transfertec Engenharia';
        $pagina->descricao_compartilhamento = $caseCliente->casesClientesIdiomas[0]->descricao_pagina . ' - Transfertec Engenharia';

        list($width, $height, $type, $attr) = getimagesize(public_path('/eng/content/cases/thumbs/' . $caseCliente->imagem));

        $pagina->imagem = [
            'endereco' => '/content/cases/thumbs/' . $caseCliente->imagem,
            'tipo' => image_type_to_mime_type($type),
            'largura' => $width,
            'altura' => $height,
        ];

        $caseClienteData = [
            'id' => $caseCliente->id,
            'nome' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->nome : null,
            'titulo_topo' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->titulo_topo : null,
            'descricao_topo' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->descricao_topo : null,
            'blocos' => $caseCliente->blocos->map(function ($bloco) {
                return [
                    'id' => $bloco->id,
                    'imagem' => $bloco->imagem ? asset('eng/content/cases/content/' . $bloco->imagem) : null,
                    'texto' => count($bloco->blocosIdiomas) ? $bloco->blocosIdiomas[0]->texto : null,
                ];
            })->values()->all(),
            'imagens' => $caseCliente->imagens->map(function ($img) {
                return [
                    'id' => $img->id,
                    'imagem' => asset('eng/content/cases/images/s/' . $img->imagem),
                ];
            })->values()->all(),
        ];

        return Inertia::render('Engenharia/CaseCliente', [
            'pagina' => $pagina,
            'caseCliente' => $caseClienteData,
        ]);
    }

}