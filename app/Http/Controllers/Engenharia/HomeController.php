<?php

namespace App\Http\Controllers\Engenharia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Engenharia\Slide;
use App\Models\Engenharia\Setor;
use App\Models\Engenharia\CaseCliente;
use App\Models\Engenharia\Depoimento;
use App\Models\Engenharia\Cliente;
use App\Models\Engenharia\Post;

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
                    'imagem' => $slide->tipo == 'imagem' ? asset('eng/content/slides/d/' . $slide->imagem) : null,
                    'imagem_mobile' => $slide->tipo == 'imagem' ? asset('eng/content/slides/m/' . $slide->imagem_mobile) : null,
                    'video' => $slide->tipo == 'video' ? asset('eng/content/slides/videos/d/' . $slide->video) : null,
                    'video_mobile' => $slide->tipo == 'video' ? asset('eng/content/slides/videos/m/' . $slide->video_mobile) : null,
                    'titulo' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->titulo : null,
                    'descricao' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->descricao : null,
                    'link' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->link : null,
                    'texto_botao' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->texto_botao : null,
                ];
            });

        $setores = Setor::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'setoresIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($setor) {
                return [
                    'id' => $setor->id,
                    'icone' => asset('eng/content/sectors/thumbs/' . $setor->icone),
                    'nome' => $setor->setoresIdiomas->isNotEmpty() ? $setor->setoresIdiomas[0]->nome : null,
                    'descricao' => $setor->setoresIdiomas->isNotEmpty() ? $setor->setoresIdiomas[0]->descricao : null,
                ];
            });

        $casesClientes = CaseCliente::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
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
            ->limit(4)
            ->get()
            ->map(function($caseCliente) {
                return [
                    'id' => $caseCliente->id,
                    'imagem' => asset('eng/content/cases/thumbs/' . $caseCliente->imagem),
                    'nome' => $caseCliente->casesClientesIdiomas->isNotEmpty() ? $caseCliente->casesClientesIdiomas[0]->nome : null,
                    'empresa' => $caseCliente->casesClientesIdiomas->isNotEmpty() ? $caseCliente->casesClientesIdiomas[0]->empresa : null,
                    'descricao' => $caseCliente->casesClientesIdiomas->isNotEmpty() ? $caseCliente->casesClientesIdiomas[0]->descricao : null,
                    'slug' => $caseCliente->slug,
                ];
            });

        $depoimentos = Depoimento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'depoimentosIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($depoimento) {
                return [
                    'id' => $depoimento->id,
                    'imagem' => asset('eng/content/testimonies/thumbs/' . $depoimento->imagem),
                    'nome' => $depoimento->depoimentosIdiomas->isNotEmpty() ? '- ' . $depoimento->depoimentosIdiomas[0]->nome : null,
                    'empresa' => $depoimento->depoimentosIdiomas->isNotEmpty() ? $depoimento->depoimentosIdiomas[0]->empresa : null,
                    'depoimento' => $depoimento->depoimentosIdiomas->isNotEmpty() ? $depoimento->depoimentosIdiomas[0]->depoimento : null,
                ];
            });

        $clientes = Cliente::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($cliente) {
                return [
                    'id' => $cliente->id,
                    'logo' => asset('eng/content/clients/thumbs/' . $cliente->logo),
                    'nome' => $cliente->nome,
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

        return Inertia::render('Engenharia/Home', [
            'slides' => $slides,
            'setores' => $setores,
            'casesClientes' => $casesClientes,
            'depoimentos' => $depoimentos,
            'clientes' => $clientes,
            'posts' => $posts
        ]);
    }
};