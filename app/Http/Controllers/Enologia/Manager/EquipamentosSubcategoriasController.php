<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\EquipamentoCategoria;
use App\Models\Enologia\EquipamentoSubcategoria;
use App\Models\Enologia\EquipamentoSubcategoriaIdioma;
use App\Models\Enologia\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Enologia\Manager\PostSubcategoryRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class EquipamentosSubcategoriasController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar() {
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $categorias = EquipamentoCategoria::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->with([
                'equipamentosCategoriasIdiomas' => function ($q) use ($idioma) {
                    $q->when($idioma, function ($r) use($idioma) {
                        $r->whereHas('idiomas', function($query) use($idioma) {
                            $query->where('codigo', $idioma);
                        });
                    })
                    ->when(!$idioma, function ($r) {
                        $r->whereHas('idiomas', function($query) {
                            $query->where('padrao', true);
                        });
                    });
                },
            ])
            ->get()
            ->map(function($categoria) {
                return [
                    'value' => $categoria->id,
                    'label' => $categoria->equipamentosCategoriasIdiomas->isNotEmpty() ? $categoria->equipamentosCategoriasIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Enologia/Manager/Equipamentos/Subcategorias/adicionar', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostSubcategoryRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $subcategoria = new EquipamentoSubcategoria;
            $subcategoria_idioma = new EquipamentoSubcategoriaIdioma;

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $subcategoria->imagem_grafico = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (EquipamentoSubcategoria::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $subcategoria->slug = $slug;
            
            $subcategoria->equipamento_categoria_id = $request->equipamento_categoria_id;

            $response = $subcategoria->save();

            $subcategoria_idioma->nome = $request->nome;

            $subcategoria_idioma->equipamento_subcategoria_id = $subcategoria->id;
            $subcategoria_idioma->idioma_id = $idioma->id;

            $response = $subcategoria_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    $image = $request->file('img')->move(public_path('eno/content/yeast/graphs/'), $subcategoria->imagem_grafico);
                }

                return to_route('Enologia.Manager.Equipamentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id) {
        if (!$id) {
            return Inertia::location(route('Enologia.Manager.Equipamentos.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $subcategoria = EquipamentoSubcategoria::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'equipamentosSubcategoriasIdiomas' => function ($q) use ($idioma) {
                    $q->when($idioma, function ($r) use($idioma) {
                        $r->whereHas('idiomas', function($query) use($idioma) {
                            $query->where('codigo', $idioma);
                        });
                    })
                    ->when(!$idioma, function ($r) {
                        $r->whereHas('idiomas', function($query) {
                            $query->where('padrao', true);
                        });
                    });
                },
            ])
            ->first();

        if(!$subcategoria) {
            return Inertia::location(route('Enologia.Manager.Equipamentos.index'));
        }

        $categorias = EquipamentoCategoria::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->with([
                'equipamentosCategoriasIdiomas' => function ($q) use ($idioma) {
                    $q->when($idioma, function ($r) use($idioma) {
                        $r->whereHas('idiomas', function($query) use($idioma) {
                            $query->where('codigo', $idioma);
                        });
                    })
                    ->when(!$idioma, function ($r) {
                        $r->whereHas('idiomas', function($query) {
                            $query->where('padrao', true);
                        });
                    });
                },
            ])
            ->get()
            ->map(function($categoria) {
                return [
                    'value' => $categoria->id,
                    'label' => $categoria->equipamentosCategoriasIdiomas->isNotEmpty() ? $categoria->equipamentosCategoriasIdiomas[0]->nome : null,
                ];
            });

        $idioma = inertia()->getShared('idioma');

        $subcategoria = [
            'id' => $subcategoria->id,
            'equipamento_categoria_id' => $subcategoria->equipamento_categoria_id,
            'imagem_grafico' => asset('eno/content/yeast/graphs/' . $subcategoria->imagem_grafico),
            'nome' => count($subcategoria->equipamentosSubcategoriasIdiomas) ? $subcategoria->equipamentosSubcategoriasIdiomas[0]->nome : null,
        ];

        return Inertia::render('Enologia/Manager/Equipamentos/Subcategorias/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'categorias' => $categorias,
            'subcategoria' => $subcategoria
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostSubcategoryRequest $request, $id) {
        if($request->ajax()){
            $subcategoria = EquipamentoSubcategoria::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $subcategoria_idioma = EquipamentoSubcategoriaIdioma::query()
                ->where([
                    'excluido' => null,
                    'equipamento_subcategoria_id' => $subcategoria->id
                ])
                ->when($idioma, function ($q) use($idioma) {
                    $q->whereHas('idiomas', function($query) use($idioma) {
                        $query->where('codigo', $idioma);
                    });
                })
                ->when(!$idioma, function ($q) {
                    $q->whereHas('idiomas', function($query) {
                        $query->where('padrao', true);
                    });
                })
                ->first();

            if (!$subcategoria) {
                return to_route('Enologia.Manager.Equipamentos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($subcategoria, 'equipamentosSubcategoriasIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Enologia.Manager.Equipamentos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Enologia.Manager.Equipamentos.index'));
            }

            if (!$subcategoria_idioma) {
                $subcategoria_idioma = new EquipamentoSubcategoriaIdioma;

                $subcategoria_idioma->equipamento_subcategoria_id = $subcategoria->id;
                $subcategoria_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $subcategoriaOriginal = $copier->copy($subcategoria);
            }

            if ($request->file('img') && $request->file('img')->isValid()) {
                $subcategoria->imagem_grafico = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }
            
            $subcategoria->equipamento_categoria_id = $request->equipamento_categoria_id;

            $slug = $subcategoria->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $subcategoria_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (EquipamentoSubcategoria::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $subcategoria->slug = $slug;

            $subcategoria_idioma->nome = $request->nome;

            $response = $subcategoria->save();
            $response = $subcategoria_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($subcategoria->imagem_grafico && isset($subcategoriaOriginal) && File::exists('eno/content/yeast/graphs/' . $subcategoriaOriginal->imagem_grafico)) {
                        File::delete('eno/content/yeast/graphs/' . $subcategoriaOriginal->imagem_grafico);
                    }

                    $image = $request->file('img')->move(public_path('eno/content/yeast/graphs/'), $subcategoria->imagem_grafico);
                }

                return to_route('Enologia.Manager.Equipamentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Enologia.Manager.Equipamentos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
    }

    /**
     * Set the specified resource as deleted.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function excluir(Request $request, $id) {
        if ($request->ajax()){
            if (!$id) {
                return $request->header('referer');
            }

            $exclusao = EquipamentoSubcategoria::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $id
                ])
                ->update([
                    'excluido' => Carbon::now()
                ]);

            if ($exclusao == true) {
                return redirect()->back()->with('message', ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']);
            } else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
            }
        }
    }

    /**
     * Set the specified resource to visible/invisible.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visibilidade(Request $request, $id) {
        if ($request->ajax()){
            if (!$id) {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Registro não encontrado!']);
            }

            $response = EquipamentoSubcategoria::query()
                ->where([
                    'id' => $id,
                    'excluido' => NULL
                ])
                ->first();

            if (!$response) {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
            }
    
            $response->visivel = 1 - $response->visivel;
            $response->save();
    
            if ($response) {
                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Visibilidade alterada com sucesso!']);
            }
            else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Visibilidade não alterada!']);
            }
        }

        return $request->header('referer');
    }

    /**
     * Update the order of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ordenar(Request $request) {
        if ($request->ajax()){
            $erros = [];

            if ($request->odr && is_array($request->odr)) {
                foreach ($request->odr as $key => $value) {
                    $registro = EquipamentoSubcategoria::query()
                        ->where([
                            'excluido' => NULL,
                            'id' => $value
                        ])
                        ->update([
                            'ordem' => $key,
                        ]);

                    $errors[] = $registro;
                }
            }

            if (!count($erros)) {
                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
            } else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registros não reordenados, tente novamente mais tarde!']);
            }
        }

        return redirect()->back();
    }
};