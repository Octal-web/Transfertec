<?php

namespace App\Http\Controllers\Enologia;

use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\InsumoCategoria;
use App\Models\Enologia\Insumo;

class InsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $insumosCategorias = InsumoCategoria::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'insumosCategoriasIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'insumosSubcategorias' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->with([
                        'insumosSubcategoriasIdiomas' => function ($subq) use ($idioma) {
                            $subq->whereHas('idiomas', function ($subr) use ($idioma) {
                                $subr->where('codigo', $idioma)
                                  ->orWhere('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        },
                        'insumos' => function ($subq) use ($idioma) {
                            $subq->where([
                                'excluido' => null,
                                'visivel' => true
                            ])
                            ->with([
                                'insumosIdiomas' => function ($insq) use ($idioma) {
                                    $insq->whereHas('idiomas', function ($insr) use ($idioma) {
                                        $insr->where('codigo', $idioma)
                                          ->orWhere('padrao', true);
                                    })
                                    ->orderBy('idioma_id', 'DESC');
                                },
                                'marca' => function ($marq) {
                                    $marq->where([
                                        'excluido' => null,
                                        'visivel' => true
                                    ]);
                                }
                            ])
                        ->orderBy('ordem', 'ASC')
                        ->orderBy('id', 'DESC');
                        }
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($categoria) {
                $marcas = collect();

                foreach ($categoria->insumosSubcategorias as $subcategoria) {
                    foreach ($subcategoria->insumos as $insumo) {
                        if ($insumo->marca) {
                            $marcas->push([
                                'id' => $insumo->marca->id,
                                'nome' => $insumo->marca->nome,
                                'logo' => $insumo->marca->logo ? asset('eno/content/brands/thumbs/' . $insumo->marca->logo) : null,
                                'link' => $insumo->marca->link,
                            ]);
                        }
                    }
                }

                $marcasUnicas = $marcas->unique('id')->values();

                return [
                    'id' => $categoria->id,
                    'nome' => $categoria->insumosCategoriasIdiomas->isNotEmpty() ? $categoria->insumosCategoriasIdiomas[0]->nome : null,
                    'slug' => $categoria->slug,
                    'subcategorias' => $categoria->insumosSubcategorias->map(function ($subcategoria) {
                        return [
                            'id' => $subcategoria->id,
                            'nome' => $subcategoria->insumosSubcategoriasIdiomas->isNotEmpty() ? $subcategoria->insumosSubcategoriasIdiomas[0]->nome : null,
                            'slug' => $subcategoria->slug,
                            'imagem_grafico' => $subcategoria->imagem_grafico ? asset('eno/content/yeast/graphs/' . $subcategoria->imagem_grafico) : null,
                            'insumos' => $subcategoria->insumos->map(function ($insumo) {
                                return [
                                    'id' => $insumo->id,
                                    'slug' => $insumo->slug,
                                    'nome' => $insumo->insumosIdiomas->isNotEmpty() ? $insumo->insumosIdiomas[0]->nome : null,
                                    'utilidade' => $insumo->insumosIdiomas->isNotEmpty() ? $insumo->insumosIdiomas[0]->utilidade : null,
                                    'descricao' => $insumo->insumosIdiomas->isNotEmpty() ? $insumo->insumosIdiomas[0]->descricao : null,
                                    'detalhes' => $insumo->insumosIdiomas->isNotEmpty() ? $insumo->insumosIdiomas[0]->detalhes : null,
                                    'ficha_tecnica' => $insumo->ficha_tecnica ? true : false,
                                    'ficha_de_seguranca' => $insumo->ficha_de_seguranca ? true : false,
                                    'imagem' => asset('eno/content/yeast/thumbs/' . $insumo->imagem),
                                ];
                            })
                        ];
                    }),
                    'marcas' => $marcasUnicas
                ];
            });

        return Inertia::render('Enologia/Insumos', [
            'insumosCategorias' => $insumosCategorias
        ]);
    }

    public function download($tipo, $id)
    {
        $tipos = [
            'ficha-tecnica' => 'ficha_tecnica',
            'ficha-seguranca' => 'ficha_de_seguranca',
        ];

        if (!array_key_exists($tipo, $tipos)) {
            abort(404);
        }

        $idioma = inertia()->getShared('idioma');

        $insumo = Insumo::query()
            ->where([
                'excluido' => null,
                'visivel' => true,
                'id' => $id
            ])
            ->with([
                'insumosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->firstOrFail();

        $field = $tipos[$tipo];
        $arquivo = $insumo->{$field};

        if (!$arquivo) {
            abort(404, 'Arquivo não encontrado no banco.');
        }

        $filePath = public_path("eno/content/yeast/files/{$arquivo}");

        if (!file_exists($filePath)) {
            abort(404, 'Arquivo físico não encontrado.');
        }

        $nomeTraduzido = $insumo->insumosIdiomas->isNotEmpty() ? $insumo->insumosIdiomas[0]->nome : 'Insumo sem nome';

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $safeName = Str::slug($nomeTraduzido) . '.' . $extension;

        return response()->download($filePath, $safeName);
    }
};