<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\InsumoSubcategoria;
use App\Models\Enologia\Insumo;
use App\Models\Enologia\InsumoIdioma;
use App\Models\Enologia\Idioma;
use App\Models\Enologia\Marca;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Enologia\Manager\PostYeastRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class InsumosProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id) {
        if (!$id) {
            return Inertia::location(route('Enologia.Manager.Home.index'));
        }

        $insumoSubcategoria = InsumoSubcategoria::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->with([
                'insumosSubcategoriasIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'insumos' => function ($q)  {
                    $q->where([
                        'excluido' => null
                    ])
                    ->with([
                        'insumosIdiomas' => function ($secq) {
                            $secq->whereHas('idiomas', function ($secr) {
                                $secr->Where('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC');
                },
            ])
            ->first();

        if(!$insumoSubcategoria) {
            return Inertia::location(route('Enologia.Manager.Insumos.index'));
        }

        $insumoSubcategoriaData = [
            'id' => $insumoSubcategoria->id,
            'nome' => count($insumoSubcategoria->insumosSubcategoriasIdiomas) ? $insumoSubcategoria->insumosSubcategoriasIdiomas[0]->nome : null,
            'insumos' => $insumoSubcategoria->insumos->map(function ($insumo) {
                return [
                    'id' => $insumo->id,
                    'visivel' => $insumo->visivel ? true : false,
                    'imagem' => asset('eno/content/yeast/thumbs/' . $insumo->imagem),
                    'nome' => count($insumo->insumosIdiomas) ? $insumo->insumosIdiomas[0]->nome : null,
                ];
            })->values()->all(),
        ];

        return Inertia::render('Enologia/Manager/Insumos/Produtos/index', [
            'insumoSubcategoria' => $insumoSubcategoriaData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar($id) {
        if (!$id) {
            return Inertia::location(route('Enologia.Manager.Insumos.Produtos.index'));
        }

        $insumoSubcategoria = InsumoSubcategoria::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->first();

        if(!$insumoSubcategoria) {
            return Inertia::location(route('Enologia.Manager.Insumos.index'));
        }

        $marcas = Marca::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->map(function($marca) {
                return [
                    'value' => $marca->id,
                    'label' => $marca->nome,
                ];
            });

        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        return Inertia::render('Enologia/Manager/Insumos/Produtos/adicionar', [
            'id' => $id,
            'marcas' => $marcas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostYeastRequest $request, $id) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $insumo = new Insumo;
            $insumo_idioma = new InsumoIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (Insumo::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $insumo->slug = $slug;

            $insumo->marca_id = $request->marca_id;
            $insumo->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            if ($request->file('arq_tec') && $request->file('arq_tec')->isValid()) {
                $insumo->ficha_tecnica = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('arq_tec')->extension());
            }

            if ($request->file('arq_seg') && $request->file('arq_seg')->isValid()) {
                $insumo->ficha_de_seguranca = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('arq_seg')->extension());
            }

            $insumo->destaque = $request->destaque ? true : false;
            $insumo->insumo_subcategoria_id = $id;

            $response = $insumo->save();

            $insumo_idioma->nome = $request->nome;
            $insumo_idioma->utilidade = $request->utilidade;
            $insumo_idioma->descricao = $request->descricao;
            $insumo_idioma->detalhes = $request->detalhes;

            $insumo_idioma->insumo_id = $insumo->id;
            $insumo_idioma->idioma_id = $idioma->id;

            $response = $insumo_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eno/content/yeast/thumbs/'), $insumo->imagem);

                if ($request->file('arq_tec') && $request->file('arq_tec')->isValid()) {
                    $file = $request->file('arq_tec')->move(public_path('eno/content/yeast/files/'), $insumo->ficha_tecnica);
                }

                if ($request->file('arq_seg') && $request->file('arq_seg')->isValid()) {
                    $file = $request->file('arq_seg')->move(public_path('eno/content/yeast/files/'), $insumo->ficha_de_seguranca);
                }

                return to_route('Enologia.Manager.Insumos.Produtos.index', ['id' => $id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Enologia.Manager.Insumos.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $insumo = Insumo::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'insumosIdiomas' => function ($q) use ($idioma) {
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

        if(!$insumo) {
            return Inertia::location(route('Enologia.Manager.Insumos.index'));
        }

        $marcas = Marca::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->map(function($marca) {
                return [
                    'value' => $marca->id,
                    'label' => $marca->nome,
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
                'categoria' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => null
                    ])
                    ->with([
                        'insumosCategoriasIdiomas' => function ($q) use ($idioma) {
                            $q->whereHas('idiomas', function ($r) use ($idioma) {
                                $r->where('codigo', $idioma)
                                ->orWhere('padrao', true);
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
                    'value' => $subcategoria->id,
                    'label' => $nomeSub . ($nomeCategoria ? ' - ' . $nomeCategoria : ''),
                ];
            });

        $idioma = inertia()->getShared('idioma');

        $insumoData = [
            'id' => $insumo->id,
            'destaque' => $insumo->destaque ? true : false,
            'marca_id' => $insumo->marca_id,
            'insumo_subcategoria_id' => $insumo->insumo_subcategoria_id,
            'imagem' => asset('eno/content/yeast/thumbs/' . $insumo->imagem),
            'ficha_tecnica' => $insumo->ficha_tecnica ? true : false,
            'ficha_de_seguranca' => $insumo->ficha_de_seguranca ? true : false,
            'nome' => count($insumo->insumosIdiomas) ? $insumo->insumosIdiomas[0]->nome : null,
            'utilidade' => count($insumo->insumosIdiomas) ? $insumo->insumosIdiomas[0]->utilidade : null,
            'descricao' => count($insumo->insumosIdiomas) ? $insumo->insumosIdiomas[0]->descricao : null,
            'detalhes' => count($insumo->insumosIdiomas) ? $insumo->insumosIdiomas[0]->detalhes : null,
        ];

        return Inertia::render('Enologia/Manager/Insumos/Produtos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'insumo' => $insumoData,
            'insumosSubcategorias' => $insumosSubcategorias,
            'marcas' => $marcas
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostYeastRequest $request, $id) {
        if($request->ajax()){
            $insumo = Insumo::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $insumo_idioma = InsumoIdioma::query()
                ->where([
                    'excluido' => null,
                    'insumo_id' => $insumo->id
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

            if (!$insumo) {
                return to_route('Enologia.Manager.Insumos.Produtos.index', ['id' => $insumo->insumo_subcategoria_id])->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($insumo, 'insumosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Enologia.Manager.Insumos.Produtos.index', ['id' => $insumo->insumo_subcategoria_id])->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Enologia.Manager.Insumos.index'));
            }

            if (!$insumo_idioma) {
                $insumo_idioma = new InsumoIdioma;

                $insumo_idioma->insumo_id = $insumo->id;
                $insumo_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $insumoOriginal = $copier->copy($insumo);
            }

            $slug = $insumo->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $insumo_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (Insumo::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $insumo->slug = $slug;

            if ($request->file('img') && $request->file('img')->isValid()) {
                $insumo->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }
            
            if ($request->file('arq_tec') && $request->file('arq_tec')->isValid()) {
                $insumo->ficha_tecnica = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('arq_tec')->extension());
            }

            if ($request->file('arq_seg') && $request->file('arq_seg')->isValid()) {
                $insumo->ficha_de_seguranca = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('arq_seg')->extension());
            }

            $insumo->destaque = $request->destaque ? true : false;
            $insumo->insumo_subcategoria_id = $request->insumo_subcategoria_id;
            $insumo->marca_id = $request->marca_id;

            $insumo_idioma->nome = $request->nome;
            $insumo_idioma->utilidade = $request->utilidade;
            $insumo_idioma->descricao = $request->descricao;
            $insumo_idioma->detalhes = $request->detalhes;

            $response = $insumo->save();
            $response = $insumo_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($insumo->imagem && isset($insumoOriginal) && File::exists('eno/content/yeast/thumbs/' . $insumoOriginal->imagem)) {
                        File::delete('eno/content/yeast/thumbs/' . $insumoOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('eno/content/yeast/thumbs/'), $insumo->imagem);
                }

                if ($request->file('arq_tec') && $request->file('arq_tec')->getError() == 0) {
                    if ($insumo->ficha_tecnica && isset($insumoOriginal) && File::exists('eno/content/yeast/files/' . $insumoOriginal->ficha_tecnica)) {
                        File::delete('eno/content/yeast/files/' . $insumoOriginal->ficha_tecnica);
                    }

                    $image = $request->file('arq_tec')->move(public_path('eno/content/yeast/files/'), $insumo->ficha_tecnica);
                }

                if ($request->file('arq_seg') && $request->file('arq_seg')->getError() == 0) {
                    if ($insumo->ficha_de_seguranca && isset($insumoOriginal) && File::exists('eno/content/yeast/files/' . $insumoOriginal->ficha_de_seguranca)) {
                        File::delete('eno/content/yeast/files/' . $insumoOriginal->ficha_de_seguranca);
                    }

                    $image = $request->file('arq_seg')->move(public_path('eno/content/yeast/files/'), $insumo->ficha_de_seguranca);
                }

                return to_route('Enologia.Manager.Insumos.Produtos.index', ['id' => $insumo->insumo_subcategoria_id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Enologia.Manager.Insumos.Produtos.index', ['id' => $insumo->insumo_subcategoria_id])->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Insumo::query()
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

            $response = Insumo::query()
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
                    $registro = Insumo::query()
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

    /**
     * Download the file of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function baixarArquivo($id, $video) {
        if (!$id || !$video || !in_array($video, ['ficha-tecnica', 'ficha-de-seguranca'])) {
            return redirect()->route('Manager.Insumos.index');
        }

        $insumo = Insumo::query()
            ->where([
                'id' => $id,
                'excluido' => NULL,
            ])
            ->first();

        if (!$insumo) {
            return redirect()->route('Manager.Insumos.index');
        }

        if ($video == 'ficha-tecnica') {
            $caminho = public_path('eno/content/yeast/files/' . $insumo->ficha_tecnica);
        } else if ($video == 'ficha-de-seguranca') {
            $caminho = public_path('eno/content/yeast/files/' . $insumo->ficha_de_seguranca);
        }

        $extensao = pathinfo($caminho)['extension'];

        if (!File::exists($caminho)) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Não foi possível encontrar o arquivo!']);
        }

        return response()->download($caminho, $insumo->slug . '-' . $video . '.' . $extensao);
    }
};