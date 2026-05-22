<?php

namespace App\Http\Controllers\Enologia;

use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\EquipamentoCategoria;
use App\Models\Enologia\Equipamento;

class EquipamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $equipamentosCategorias = EquipamentoCategoria::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'equipamentosCategoriasIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'equipamentosSubcategorias' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->with([
                        'equipamentosSubcategoriasIdiomas' => function ($subq) use ($idioma) {
                            $subq->whereHas('idiomas', function ($subr) use ($idioma) {
                                $subr->where('codigo', $idioma)
                                  ->orWhere('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        },
                        'equipamentos' => function ($subq) use ($idioma) {
                            $subq->where([
                                'excluido' => null,
                                'visivel' => true
                            ])
                            ->with([
                                'equipamentosIdiomas' => function ($insq) use ($idioma) {
                                    $insq->whereHas('idiomas', function ($insr) use ($idioma) {
                                        $insr->where('codigo', $idioma)
                                          ->orWhere('padrao', true);
                                    })
                                    ->orderBy('idioma_id', 'DESC');
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
                return [
                    'id' => $categoria->id,
                    'nome' => $categoria->equipamentosCategoriasIdiomas->isNotEmpty() ? $categoria->equipamentosCategoriasIdiomas[0]->nome : null,
                    'slug' => $categoria->slug,
                    'subcategorias' => $categoria->equipamentosSubcategorias->map(function ($subcategoria) {
                        return [
                            'id' => $subcategoria->id,
                            'nome' => $subcategoria->equipamentosSubcategoriasIdiomas->isNotEmpty() ? $subcategoria->equipamentosSubcategoriasIdiomas[0]->nome : null,
                            'slug' => $subcategoria->slug,
                            'imagem_grafico' => asset('eno/content/yeast/graphs/' . $subcategoria->imagem_grafico),
                            'equipamentos' => $subcategoria->equipamentos->map(function ($equipamento) {
                                return [
                                    'id' => $equipamento->id,
                                    'slug' => $equipamento->slug,
                                    'nome' => $equipamento->equipamentosIdiomas->isNotEmpty() ? $equipamento->equipamentosIdiomas[0]->nome : null,
                                    'utilidade' => $equipamento->equipamentosIdiomas->isNotEmpty() ? $equipamento->equipamentosIdiomas[0]->utilidade : null,
                                    'descricao' => $equipamento->equipamentosIdiomas->isNotEmpty() ? $equipamento->equipamentosIdiomas[0]->descricao : null,
                                    'detalhes' => $equipamento->equipamentosIdiomas->isNotEmpty() ? $equipamento->equipamentosIdiomas[0]->detalhes : null,
                                    'catalogo' => $equipamento->catalogo ? true : false,
                                    'video' => $equipamento->video ? true : false,
                                    'imagem' => asset('eno/content/equipment/thumbs/' . $equipamento->imagem),
                                ];
                            })
                        ];
                    }),
                ];
            });

        return Inertia::render('Enologia/Equipamentos', [
            'equipamentosCategorias' => $equipamentosCategorias
        ]);
    }

    public function download($tipo, $id)
    {
        $tipos = [
            'catalogo' => 'catalogo',
            'video-demonstrativo' => 'video',
        ];

        if (!array_key_exists($tipo, $tipos)) {
            abort(404);
        }

        $idioma = inertia()->getShared('idioma');

        $equipamento = Equipamento::query()
            ->where([
                'excluido' => null,
                'visivel' => true,
                'id' => $id
            ])
            ->with([
                'equipamentosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->firstOrFail();

        $field = $tipos[$tipo];
        $arquivo = $equipamento->{$field};

        if (!$arquivo) {
            abort(404, 'Arquivo não encontrado no banco.');
        }

        $filePath = public_path("eno/content/equipment/files/{$arquivo}");

        if (!file_exists($filePath)) {
            abort(404, 'Arquivo físico não encontrado.');
        }

        $nomeTraduzido = $equipamento->equipamentosIdiomas->isNotEmpty() ? $equipamento->equipamentosIdiomas[0]->nome : 'Equipamento sem nome';

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $safeName = Str::slug($nomeTraduzido) . '.' . $extension;

        return response()->download($filePath, $safeName);
    }
};