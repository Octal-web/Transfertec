<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\InsumoCategoria;
use App\Models\Enologia\InsumoSubcategoria;
use App\Models\Enologia\Marca;

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
                'excluido' => NULL
            ])
            ->with([
                'insumosCategoriasIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($categoria) {
                return [
                    'id' => $categoria->id,
                    'visivel' => $categoria->visivel,
                    'nome' => $categoria->insumosCategoriasIdiomas->isNotEmpty() ? $categoria->insumosCategoriasIdiomas[0]->nome : null,
                ];
            });

        $insumosSubcategorias = InsumoSubcategoria::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'insumosSubcategoriasIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'categoria' => function ($q) {
                    $q->where([
                        'excluido' => null
                    ])
                    ->with([
                        'insumosCategoriasIdiomas' => function ($subq) {
                            $subq->whereHas('idiomas', function ($subr) {
                                $subr->Where('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
                    ]);
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($subcategoria) {
                $nomeSub = $subcategoria->insumosSubcategoriasIdiomas->isNotEmpty()
                    ? $subcategoria->insumosSubcategoriasIdiomas[0]->nome
                    : null;

                $nomeCategoria = $subcategoria->categoria->insumosCategoriasIdiomas->isNotEmpty()
                    ? $subcategoria->categoria->insumosCategoriasIdiomas[0]->nome
                    : null;

                return [
                    'id' => $subcategoria->id,
                    'visivel' => $subcategoria->visivel,
                    'nome' => $nomeSub . ($nomeCategoria ? ' - ' . $nomeCategoria : ''),
                ];
            });

        $marcas = Marca::query()
            ->where([
                'excluido' => NULL
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($marca) {
                return [
                    'id' => $marca->id,
                    'visivel' => $marca->visivel,
                    'nome' => $marca->nome,
                    'imagem' => asset('eno/content/brands/thumbs/' . $marca->logo),
                ];
            });

        return Inertia::render('Enologia/Manager/Insumos/index', [
            'insumosCategorias' => $insumosCategorias,
            'insumosSubcategorias' => $insumosSubcategorias,
            'marcas' => $marcas,
        ]);
    }
};