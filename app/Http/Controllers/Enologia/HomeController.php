<?php

namespace App\Http\Controllers\Enologia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\Slide;
use App\Models\Enologia\Solucao;
use App\Models\Enologia\Insumo;
use App\Models\Enologia\Equipamento;
use App\Models\Enologia\Parceiro;
use App\Models\Enologia\Post;

use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $slides = Slide::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'slidesIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($slide) {
                return [
                    'id' => $slide->id,
                    'tipo' => $slide->tipo,
                    'imagem' => $slide->tipo == 'imagem' ? asset('eno/content/slides/d/' . $slide->imagem) : null,
                    'imagem_mobile' => $slide->tipo == 'imagem' ? asset('eno/content/slides/m/' . $slide->imagem_mobile) : null,
                    'video' => $slide->tipo == 'video' ? asset('eno/content/slides/videos/d/' . $slide->video) : null,
                    'video_mobile' => $slide->tipo == 'video' ? asset('eno/content/slides/videos/m/' . $slide->video_mobile) : null,
                    'titulo' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->titulo : null,
                    'descricao' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->descricao : null,
                    'link' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->link : null,
                    'texto_botao' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->texto_botao : null,
                ];
            });

        $solucoes = Solucao::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'solucoesIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($solucao) {
                return [
                    'id' => $solucao->id,
                    'icone' => asset('eno/content/solutions/thumbs/' . $solucao->icone),
                    'titulo' => $solucao->solucoesIdiomas->isNotEmpty() ? $solucao->solucoesIdiomas[0]->titulo : null,
                    'descricao' => $solucao->solucoesIdiomas->isNotEmpty() ? $solucao->solucoesIdiomas[0]->descricao : null,
                ];
            });

        $insumos = Insumo::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'destaque' => true
            ])
            ->with([
                'insumosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'subcategoria' => function ($query) use ($idioma) {
                    $query->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with('insumosSubcategoriasIdiomas', function ($q) use ($idioma) {
                        $q->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                              ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    });
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($insumo) {
                return [
                    'id' => $insumo->id,
                    'imagem' => asset('eno/content/yeast/thumbs/' . $insumo->imagem),
                    'nome' => $insumo->insumosIdiomas->isNotEmpty() ? $insumo->insumosIdiomas[0]->nome : null,
                    'subcategoria' => $insumo->subcategoria->insumosSubcategoriasIdiomas->isNotEmpty() ? $insumo->subcategoria->insumosSubcategoriasIdiomas[0]->nome : null,
                    'slug' => $insumo->slug,
                ];
            });

        $equipamentos = Equipamento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'destaque' => true
            ])
            ->with([
                'equipamentosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'subcategoria' => function ($query) use ($idioma) {
                    $query->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with('equipamentosSubcategoriasIdiomas', function ($q) use ($idioma) {
                        $q->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                              ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    });
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($equipamento) {
                return [
                    'id' => $equipamento->id,
                    'imagem' => asset('eno/content/equipment/thumbs/' . $equipamento->imagem),
                    'nome' => $equipamento->equipamentosIdiomas->isNotEmpty() ? $equipamento->equipamentosIdiomas[0]->nome : null,
                    'subcategoria' => $equipamento->subcategoria->equipamentosSubcategoriasIdiomas->isNotEmpty() ? $equipamento->subcategoria->equipamentosSubcategoriasIdiomas[0]->nome : null,
                    'slug' => $equipamento->slug,
                ];
            });

        $parceiros = Parceiro::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($parceiro) {
                return [
                    'id' => $parceiro->id,
                    'logo' => asset('eno/content/partners/thumbs/' . $parceiro->logo),
                    'nome' => $parceiro->nome,
                    'link' => $parceiro->link,
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
                    'imagem' => asset('eno/content/posts/thumbs/' . $post->imagem),
                    'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                    'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                    'categoria_slug' => $post->categoria->slug,
                    'slug' => $post->slug
                ];
            });

        return Inertia::render('Enologia/Home', [
            'slides' => $slides,
            'solucoes' => $solucoes,
            'insumos' => $insumos,
            'equipamentos' => $equipamentos,
            'parceiros' => $parceiros,
            'posts' => $posts
        ]);
    }
};