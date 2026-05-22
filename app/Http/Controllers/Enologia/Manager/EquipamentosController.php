<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\EquipamentoCategoria;
use App\Models\Enologia\EquipamentoSubcategoria;

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
                'excluido' => NULL
            ])
            ->with([
                'equipamentosCategoriasIdiomas' => function ($q) {
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
                    'nome' => $categoria->equipamentosCategoriasIdiomas->isNotEmpty() ? $categoria->equipamentosCategoriasIdiomas[0]->nome : null,
                ];
            });

        $equipamentosSubcategorias = EquipamentoSubcategoria::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'equipamentosSubcategoriasIdiomas' => function ($q) {
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
                        'equipamentosCategoriasIdiomas' => function ($subq) {
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
                $nomeSub = $subcategoria->equipamentosSubcategoriasIdiomas->isNotEmpty()
                    ? $subcategoria->equipamentosSubcategoriasIdiomas[0]->nome
                    : null;

                $nomeCategoria = $subcategoria->categoria->equipamentosCategoriasIdiomas->isNotEmpty()
                    ? $subcategoria->categoria->equipamentosCategoriasIdiomas[0]->nome
                    : null;

                return [
                    'id' => $subcategoria->id,
                    'visivel' => $subcategoria->visivel,
                    'nome' => $nomeSub . ($nomeCategoria ? ' - ' . $nomeCategoria : ''),
                ];
            });

        return Inertia::render('Enologia/Manager/Equipamentos/index', [
            'equipamentosCategorias' => $equipamentosCategorias,
            'equipamentosSubcategorias' => $equipamentosSubcategorias,
        ]);
    }
};