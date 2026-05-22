<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Engenharia\Produto;
use App\Models\Engenharia\ProdutoIdioma;
use App\Models\Engenharia\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Engenharia\Manager\PostProductRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $produtos = Produto::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'produtosIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($produto) {
                return [
                    'id' => $produto->id,
                    'visivel' => $produto->visivel,
                    'imagem' => asset('eng/content/products/thumbs/' . $produto->imagem),
                    'nome' => $produto->produtosIdiomas->isNotEmpty() ? $produto->produtosIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Engenharia/Manager/Produtos/index', [
            'produtos' => $produtos
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

        return Inertia::render('Engenharia/Manager/Produtos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostProductRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $produto = new Produto;
            $produto_idioma = new ProdutoIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (Produto::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $produto->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            $produto->slug = $slug;
            $produto->layout = $request->layout;

            $response = $produto->save();

            $produto_idioma->nome = $request->nome;
            $produto_idioma->setor = $request->setor;
            $produto_idioma->descricao = $request->descricao;
            $produto_idioma->detalhes = $request->detalhes;

            $produto_idioma->produto_id = $produto->id;
            $produto_idioma->idioma_id = $idioma->id;

            $response = $produto_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eng/content/products/thumbs/'), $produto->imagem);

                return to_route('Engenharia.Manager.Produtos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Engenharia.Manager.Produtos.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $produto = Produto::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'produtosIdiomas' => function ($q) use ($idioma) {
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

        if(!$produto) {
            return Inertia::location(route('Engenharia.Manager.Produtos.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $produto = [
            'id' => $produto->id,
            'imagem' => asset('eng/content/products/thumbs/' . $produto->imagem),
            'nome' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->nome : null,
            'layout' => $produto->layout,
            'setor' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->setor : null,
            'descricao' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->descricao : null,
            'detalhes' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->detalhes : null
        ];

        return Inertia::render('Engenharia/Manager/Produtos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'produto' => $produto
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostProductRequest $request, $id) {
        if($request->ajax()){
            $produto = Produto::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $produto_idioma = ProdutoIdioma::query()
                ->where([
                    'excluido' => null,
                    'produto_id' => $produto->id
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

            if (!$produto) {
                return to_route('Engenharia.Manager.Produtos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($produto, 'produtosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Engenharia.Manager.Produtos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Engenharia.Manager.Produtos.index'));
            }

            if (!$produto_idioma) {
                $produto_idioma = new ProdutoIdioma;

                $produto_idioma->produto_id = $produto->id;
                $produto_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $produtoOriginal = $copier->copy($produto);
            }

            $slug = $produto->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $produto_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (Produto::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $produto->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $produto->slug = $slug;
            $produto->layout = $request->layout;

            $produto_idioma->nome = $request->nome;
            $produto_idioma->setor = $request->setor;
            $produto_idioma->descricao = $request->descricao;
            $produto_idioma->detalhes = $request->detalhes;

            $response = $produto->save();
            $response = $produto_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($produto->imagem && isset($produtoOriginal) && File::exists('eng/content/products/thumbs/' . $produtoOriginal->imagem)) {
                        File::delete('eng/content/products/thumbs/' . $produtoOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('eng/content/products/thumbs/'), $produto->imagem);
                }

                return to_route('Engenharia.Manager.Produtos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Engenharia.Manager.Produtos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Produto::query()
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

            $response = Produto::query()
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
                    $registro = Produto::query()
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