<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Engenharia\CaseCliente;
use App\Models\Engenharia\CaseClienteIdioma;
use App\Models\Engenharia\Produto;
use App\Models\Engenharia\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Engenharia\Manager\PostCaseRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

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
                'excluido' => NULL
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
            ->get()
            ->map(function($caseCliente) {
                return [
                    'id' => $caseCliente->id,
                    'visivel' => $caseCliente->visivel,
                    'imagem' => asset('eng/content/cases/thumbs/' . $caseCliente->imagem),
                    'nome' => $caseCliente->casesClientesIdiomas->isNotEmpty() ? $caseCliente->casesClientesIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Engenharia/Manager/Cases/index', [
            'casesClientes' => $casesClientes
        ]);
    }
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

        $produtos = Produto::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'produtosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->get()
            ->map(function ($produto) {
                return [
                    'value' => $produto->id,
                    'label' => $produto->produtosIdiomas->isNotEmpty() ? $produto->produtosIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Engenharia/Manager/Cases/adicionar', [
            'produtos' => $produtos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostCaseRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $caseCliente = new CaseCliente;
            $caseCliente_idioma = new CaseClienteIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (CaseCliente::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $caseCliente->produto_id = $request->produto_id;
            $caseCliente->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            $caseCliente->slug = $slug;

            $response = $caseCliente->save();

            $caseCliente_idioma->nome = $request->nome;
            $caseCliente_idioma->empresa = $request->empresa ? $request->empresa : null;
            $caseCliente_idioma->descricao = $request->descricao;
            $caseCliente_idioma->titulo_topo = $request->titulo_topo;
            $caseCliente_idioma->descricao_topo = $request->descricao_topo;
            $caseCliente_idioma->titulo_pagina = $request->titulo_pagina;
            $caseCliente_idioma->descricao_pagina = $request->descricao_pagina;

            $caseCliente_idioma->caseCliente_id = $caseCliente->id;
            $caseCliente_idioma->idioma_id = $idioma->id;

            $response = $caseCliente_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eng/content/cases/thumbs/'), $caseCliente->imagem);

                return to_route('Engenharia.Manager.Cases.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Engenharia.Manager.Cases.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $caseCliente = CaseCliente::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'casesClientesIdiomas' => function ($q) use ($idioma) {
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

        if(!$caseCliente) {
            return Inertia::location(route('Engenharia.Manager.Cases.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $caseCliente = [
            'id' => $caseCliente->id,
            'imagem' => asset('eng/content/cases/thumbs/' . $caseCliente->imagem),
            'nome' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->nome : null,
            'empresa' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->empresa : null,
            'descricao' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->descricao : null,
            'produto_id' => $caseCliente->produto_id,
            'titulo_topo' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->titulo_topo : null,
            'descricao_topo' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->descricao_topo : null,
            'titulo_pagina' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->titulo_pagina : null,
            'descricao_pagina' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->descricao_pagina : null,
        ];

        $produtos = Produto::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'produtosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->get()
            ->map(function ($produto) {
                return [
                    'value' => $produto->id,
                    'label' => $produto->produtosIdiomas->isNotEmpty() ? $produto->produtosIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Engenharia/Manager/Cases/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'caseCliente' => $caseCliente,
            'produtos' => $produtos
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostCaseRequest $request, $id) {
        if($request->ajax()){
            $caseCliente = CaseCliente::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $caseCliente_idioma = CaseClienteIdioma::query()
                ->where([
                    'excluido' => null,
                    'case_cliente_id' => $caseCliente->id
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

            if (!$caseCliente) {
                return to_route('Engenharia.Manager.Cases.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($caseCliente, 'casesClientesIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Engenharia.Manager.Cases.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Engenharia.Manager.Cases.index'));
            }

            if (!$caseCliente_idioma) {
                $caseCliente_idioma = new CaseClienteIdioma;

                $caseCliente_idioma->caseCliente_id = $caseCliente->id;
                $caseCliente_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $caseClienteOriginal = $copier->copy($caseCliente);
            }

            $slug = $caseCliente->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $caseCliente_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (CaseCliente::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $caseCliente->slug = $slug;
            $caseCliente->produto_id = $request->produto_id;

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $caseCliente->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $caseCliente_idioma->nome = $request->nome;
            $caseCliente_idioma->empresa = $request->empresa ? $request->empresa : null;
            $caseCliente_idioma->descricao = $request->descricao;
            $caseCliente_idioma->titulo_topo = $request->titulo_topo;
            $caseCliente_idioma->descricao_topo = $request->descricao_topo;
            $caseCliente_idioma->titulo_pagina = $request->titulo_pagina;
            $caseCliente_idioma->descricao_pagina = $request->descricao_pagina;

            $response = $caseCliente->save();
            $response = $caseCliente_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($caseCliente->imagem && isset($caseClienteOriginal) && File::exists('eng/content/cases/thumbs/' . $caseClienteOriginal->imagem)) {
                        File::delete('eng/content/cases/thumbs/' . $caseClienteOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('eng/content/cases/thumbs/'), $caseCliente->imagem);
                }

                return to_route('Engenharia.Manager.Cases.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Engenharia.Manager.Cases.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = CaseCliente::query()
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

            $response = CaseCliente::query()
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
                    $registro = CaseCliente::query()
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
}