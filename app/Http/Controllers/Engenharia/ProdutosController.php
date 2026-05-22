<?php

namespace App\Http\Controllers\Engenharia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Engenharia\Produto;
use App\Models\Engenharia\Post;

use Carbon\Carbon;

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
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($produto) {
                return [
                    'id' => $produto->id,
                    'imagem' => asset('eng/content/products/thumbs/' . $produto->imagem),
                    'layout' => $produto->layout,
                    'nome' => $produto->produtosIdiomas->isNotEmpty() ? $produto->produtosIdiomas[0]->nome : null,
                    'setor' => $produto->produtosIdiomas->isNotEmpty() ? $produto->produtosIdiomas[0]->setor : null,
                    'descricao' => $produto->produtosIdiomas->isNotEmpty() ? $produto->produtosIdiomas[0]->descricao : null,
                    'detalhes' => $produto->produtosIdiomas->isNotEmpty() ? $produto->produtosIdiomas[0]->detalhes : null,
                    'slug' => $produto->slug,
                ];
            });

        $posts = Post::query()
            ->where([
                'excluido' => NULL,
            ])
            ->where(function($q) {
                $q->where('visivel', true)
                  ->orWhere('publicado', '>=', Carbon::now());
            })
            ->with([
                'postsIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('publicado', 'DESC')
            ->orderBy('ordem', 'ASC')
            ->limit(3)
            ->get()
            ->map(function($post) {
                return [
                    'id' => $post->id,
                    'imagem' => asset('eng/content/posts/thumbs/' . $post->imagem),
                    'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                    'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                    'categoria_slug' => $post->categoria->slug,
                    'slug' => $post->slug
                ];
            });

        return Inertia::render('Engenharia/Produtos', [
            'produtos' => $produtos,
            'posts' => $posts
        ]);
    }
};