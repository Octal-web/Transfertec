<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\EquipamentoSubcategoria;
use App\Models\Enologia\Equipamento;
use App\Models\Enologia\EquipamentoIdioma;
use App\Models\Enologia\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Enologia\Manager\PostEquipmentRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class EquipamentosProdutosController extends Controller
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

        $equipamentoSubcategoria = EquipamentoSubcategoria::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->with([
                'equipamentosSubcategoriasIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'equipamentos' => function ($q)  {
                    $q->where([
                        'excluido' => null
                    ])
                    ->with([
                        'equipamentosIdiomas' => function ($secq) {
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

        if(!$equipamentoSubcategoria) {
            return Inertia::location(route('Enologia.Manager.Equipamentos.index'));
        }

        $equipamentoSubcategoriaData = [
            'id' => $equipamentoSubcategoria->id,
            'nome' => count($equipamentoSubcategoria->equipamentosSubcategoriasIdiomas) ? $equipamentoSubcategoria->equipamentosSubcategoriasIdiomas[0]->nome : null,
            'equipamentos' => $equipamentoSubcategoria->equipamentos->map(function ($equipamento) {
                return [
                    'id' => $equipamento->id,
                    'visivel' => $equipamento->visivel ? true : false,
                    'imagem' => asset('eno/content/equipment/thumbs/' . $equipamento->imagem),
                    'nome' => count($equipamento->equipamentosIdiomas) ? $equipamento->equipamentosIdiomas[0]->nome : null,
                ];
            })->values()->all(),
        ];

        return Inertia::render('Enologia/Manager/Equipamentos/Produtos/index', [
            'equipamentoSubcategoria' => $equipamentoSubcategoriaData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar($id) {
        if (!$id) {
            return Inertia::location(route('Enologia.Manager.Equipamentos.Produtos.index'));
        }

        $equipamentoSubcategoria = EquipamentoSubcategoria::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->first();

        if(!$equipamentoSubcategoria) {
            return Inertia::location(route('Enologia.Manager.Equipamentos.index'));
        }

        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        return Inertia::render('Enologia/Manager/Equipamentos/Produtos/adicionar', [
            'id' => $id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(Request $request, $id) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $equipamento = new Equipamento;
            $equipamento_idioma = new EquipamentoIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (Equipamento::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $equipamento->slug = $slug;

            $equipamento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            if ($request->file('arq') && $request->file('arq')->isValid()) {
                $equipamento->catalogo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('arq')->extension());
            }

            if ($request->file('vid') && $request->file('vid')->isValid()) {
                $equipamento->video = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('vid')->extension());
            }

            $equipamento->destaque = $request->destaque ? true : false;
            $equipamento->equipamento_subcategoria_id = $id;

            $response = $equipamento->save();

            $equipamento_idioma->nome = $request->nome;
            $equipamento_idioma->descricao = $request->descricao;
            $equipamento_idioma->detalhes = $request->detalhes;

            $equipamento_idioma->equipamento_id = $equipamento->id;
            $equipamento_idioma->idioma_id = $idioma->id;

            $response = $equipamento_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eno/content/equipment/thumbs/'), $equipamento->imagem);

                if ($request->file('arq') && $request->file('arq')->isValid()) {
                    $file = $request->file('arq')->move(public_path('eno/content/equipment/files/'), $equipamento->catalogo);
                }

                if ($request->file('vid') && $request->file('vid')->isValid()) {
                    $file = $request->file('vid')->move(public_path('eno/content/equipment/files/'), $equipamento->video);
                }

                return to_route('Enologia.Manager.Equipamentos.Produtos.index', ['id' => $id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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

        $equipamento = Equipamento::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'equipamentosIdiomas' => function ($q) use ($idioma) {
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

        if(!$equipamento) {
            return Inertia::location(route('Enologia.Manager.Equipamentos.index'));
        }

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
                    'value' => $subcategoria->id,
                    'label' => $nomeSub . ($nomeCategoria ? ' - ' . $nomeCategoria : ''),
                ];
            });

        $idioma = inertia()->getShared('idioma');

        $equipamentoData = [
            'id' => $equipamento->id,
            'destaque' => $equipamento->destaque ? true : false,
            'equipamento_subcategoria_id' => $equipamento->equipamento_subcategoria_id,
            'imagem' => asset('eno/content/equipment/thumbs/' . $equipamento->imagem),
            'catalogo' => $equipamento->catalogo ? true : false,
            'video' => $equipamento->video ? true : false,
            'nome' => count($equipamento->equipamentosIdiomas) ? $equipamento->equipamentosIdiomas[0]->nome : null,
            'descricao' => count($equipamento->equipamentosIdiomas) ? $equipamento->equipamentosIdiomas[0]->descricao : null,
            'detalhes' => count($equipamento->equipamentosIdiomas) ? $equipamento->equipamentosIdiomas[0]->detalhes : null,
        ];

        return Inertia::render('Enologia/Manager/Equipamentos/Produtos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'equipamento' => $equipamentoData,
            'equipamentosSubcategorias' => $equipamentosSubcategorias
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(Request $request, $id) {
        if($request->ajax()){
            $equipamento = Equipamento::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $equipamento_idioma = EquipamentoIdioma::query()
                ->where([
                    'excluido' => null,
                    'equipamento_id' => $equipamento->id
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

            if (!$equipamento) {
                return to_route('Enologia.Manager.Equipamentos.Produtos.index', ['id' => $equipamento->equipamento_subcategoria_id])->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($equipamento, 'equipamentosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Enologia.Manager.Equipamentos.Produtos.index', ['id' => $equipamento->equipamento_subcategoria_id])->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Enologia.Manager.Equipamentos.index'));
            }

            if (!$equipamento_idioma) {
                $equipamento_idioma = new EquipamentoIdioma;

                $equipamento_idioma->equipamento_id = $equipamento->id;
                $equipamento_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $equipamentoOriginal = $copier->copy($equipamento);
            }

            $slug = $equipamento->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $equipamento_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (Equipamento::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $equipamento->destaque = $request->destaque ? true : false;
            $equipamento->equipamento_subcategoria_id = $request->equipamento_subcategoria_id;
            $equipamento->slug = $slug;

            if ($request->file('img') && $request->file('img')->isValid()) {
                $equipamento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $equipamento_idioma->nome = $request->nome;
            $equipamento_idioma->descricao = $request->descricao;
            $equipamento_idioma->detalhes = $request->detalhes;

            $response = $equipamento->save();
            $response = $equipamento_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($equipamento->imagem && isset($equipamentoOriginal) && File::exists('eno/content/equipment/thumbs/' . $equipamentoOriginal->imagem)) {
                        File::delete('eno/content/equipment/thumbs/' . $equipamentoOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('eno/content/equipment/thumbs/'), $equipamento->imagem);
                }

                if ($request->file('arq') && $request->file('arq')->getError() == 0) {
                    if ($equipamento->catalogo && isset($equipamentoOriginal) && File::exists('eno/content/equipment/files/' . $equipamentoOriginal->catalogo)) {
                        File::delete('eno/content/equipment/files/' . $equipamentoOriginal->catalogo);
                    }

                    $image = $request->file('arq')->move(public_path('eno/content/equipment/files/'), $equipamento->catalogo);
                }

                if ($request->file('vid') && $request->file('vid')->getError() == 0) {
                    if ($equipamento->video && isset($equipamentoOriginal) && File::exists('eno/content/equipment/files/' . $equipamentoOriginal->video)) {
                        File::delete('eno/content/equipment/files/' . $equipamentoOriginal->video);
                    }

                    $image = $request->file('vid')->move(public_path('eno/content/equipment/files/'), $equipamento->video);
                }

                return to_route('Enologia.Manager.Equipamentos.Produtos.index', ['id' => $equipamento->equipamento_subcategoria_id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Enologia.Manager.Equipamentos.Produtos.index', ['id' => $equipamento->equipamento_subcategoria_id])->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Equipamento::query()
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

            $response = Equipamento::query()
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
                    $registro = Equipamento::query()
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
        if (!$id || !$video || !in_array($video, ['catalogo', 'video-demonstrativo'])) {
            return redirect()->route('Manager.Equipamentos.index');
        }

        $equipamento = Equipamento::query()
            ->where([
                'id' => $id,
                'excluido' => NULL,
            ])
            ->first();

        if (!$equipamento) {
            return redirect()->route('Manager.Equipamentos.index');
        }

        if ($video == 'catalogo') {
            $caminho = public_path('eno/content/equipment/files/' . $equipamento->catalogo);
        } else if ($video == 'video-demonstrativo') {
            $caminho = public_path('eno/content/equipment/files/' . $equipamento->video);
        }

        $extensao = pathinfo($caminho)['extension'];

        if (!File::exists($caminho)) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Não foi possível encontrar o arquivo!']);
        }

        return response()->download($caminho, $equipamento->slug . '-' . $video . '.' . $extensao);
    }
};