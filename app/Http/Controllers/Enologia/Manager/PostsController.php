<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\Post;
use App\Models\Enologia\PostIdioma;
use App\Models\Enologia\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Enologia\Manager\PostPostRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class PostsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar() {
        return Inertia::render('Enologia/Manager/Posts/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostPostRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');

            $post = new Post;
            $post_idioma = new PostIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (Post::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $post->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            $post->slug = $request->slug;

            $response = $post->save();

            $post_idioma->titulo = $request->titulo;
            $post_idioma->previa = $request->previa;
            $post_idioma->conteudo = $request->conteudo;
            $post_idioma->titulo_pagina = $request->titulo_pagina;
            $post_idioma->descricao_pagina = $request->descricao_pagina;

            $post_idioma->post_id = $post->id;
            $post_idioma->idioma_id = $idioma->id;

            $response = $post_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eno/content/posts/thumbs/'), $post->imagem);

                return to_route('Enologia.Manager.Home.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Enologia.Manager.Home.index'));
        }

        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $post = Post::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'postsIdiomas' => function ($q) use ($idioma) {
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

        if(!$post) {
            return Inertia::location(route('Enologia.Manager.News.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $post = [
            'id' => $post->id,
            'imagem' => asset('eno/content/posts/thumbs/' . $post->imagem),
            'titulo' => count($post->postsIdiomas) ? $post->postsIdiomas[0]->titulo : null,
            'previa' => count($post->postsIdiomas) ? $post->postsIdiomas[0]->previa : null,
            'conteudo' => count($post->postsIdiomas) ? $post->postsIdiomas[0]->conteudo : null,
            'titulo_pagina' => count($post->postsIdiomas) ? $post->postsIdiomas[0]->titulo_pagina : null,
            'descricao_pagina' => count($post->postsIdiomas) ? $post->postsIdiomas[0]->descricao_pagina : null
        ];

        return Inertia::render('Enologia/Manager/Posts/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'postItem' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostPostRequest $request, $id) {
        if($request->ajax()){
            $post = Post::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $post_idioma = PostIdioma::query()
                ->where([
                    'excluido' => null,
                    'post_id' => $post->id
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

            if (!$post) {
                return to_route('Enologia.Manager.News.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($post, 'postsIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Enologia.Manager.News.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Enologia.Manager.News.index'));
            }

            if (!$post_idioma) {
                $post_idioma = new PostIdioma;

                $post_idioma->post_id = $post->id;
                $post_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $postOriginal = $copier->copy($post);
            }

            $slug = $post->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $post_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (Post::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $post->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $post->slug = $slug;
            
            $post_idioma->titulo = $request->titulo;
            $post_idioma->previa = $request->previa;
            $post_idioma->conteudo = $request->conteudo;
            $post_idioma->titulo_pagina = $request->titulo_pagina;
            $post_idioma->descricao_pagina = $request->descricao_pagina;

            $response = $post->save();
            $response = $post_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($post->imagem && isset($postOriginal) && File::exists('eno/content/posts/thumbs/' . $postOriginal->imagem)) {
                        File::delete('eno/content/posts/thumbs/' . $postOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('eno/content/posts/thumbs/'), $post->imagem);
                }

                return to_route('Enologia.Manager.News.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Enologia.Manager.News.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Post::query()
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

            $response = Post::query()
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
                    $registro = Post::query()
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