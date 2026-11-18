<?php

namespace App\Http\Controllers;

use App\Models\Engenharia\Conteudo as EngenhariaConteudo;
use App\Models\Engenharia\Pagina as EngenhariaPagina;
use App\Models\Engenharia\Post as EngenhariaPost;
use App\Models\Enologia\Conteudo as EnologiaConteudo;
use App\Models\Enologia\Pagina as EnologiaPagina;
use App\Models\Enologia\Post as EnologiaPost;

use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController
{
    public function __invoke()
    {
        $sitemap = Sitemap::create();

        $paginas = EnologiaPagina::query()
            ->whereNull('excluido')
            ->get()
            ->map(fn($pagina) => [
                'pagina' => $pagina,
                'conteudo' => EnologiaConteudo::class,
                'modulo' => 'Enologia'
            ])
            ->concat(
                EngenhariaPagina::query()
                    ->whereNull('excluido')
                    ->get()
                    ->map(fn($pagina) => [
                        'pagina' => $pagina,
                        'conteudo' => EngenhariaConteudo::class,
                        'modulo' => 'Engenharia'
                    ])
            );

        foreach ($paginas as $item) {
            $pagina = $item['pagina'];

            $route = $item['modulo'] . '.' . $pagina->controladora . '.' . $pagina->acao;

            if (!Route::has($route) || $pagina->acao !== 'index') {
                continue;
            }

            $conteudoModel = $item['conteudo'];

            $ultimaModificacao = $conteudoModel::query()
                ->where([
                    'controladora' => $pagina->controladora,
                    'acao' => $pagina->acao,
                    'excluido' => null
                ])
                ->orderByDesc('modificado')
                ->first();

            $sitemap->add(
                Url::create(route($route))
                    ->setLastModificationDate(
                        $ultimaModificacao?->modificado
                            ?? $ultimaModificacao?->criado
                            ?? $pagina->modificado
                            ?? $pagina->criado
                    )
                    ->setPriority(
                        in_array($pagina->controladora, ['Politicas', 'Manual'])
                            ? 0.3
                            : 1.0
                    )
            );
        }

        $posts = [
            [
                'model' => EngenhariaPost::class,
                'route' => 'Engenharia.News.post',
            ],
            [
                'model' => EnologiaPost::class,
                'route' => 'Enologia.News.post',
            ],
        ];

        foreach ($posts as $config) {
            if (!Route::has($config['route'])) {
                continue;
            }

            $config['model']::query()
                ->with('categoria')
                ->where([
                    'visivel' => true,
                    'excluido' => null
                ])
                ->get()
                ->each(function ($post) use ($sitemap, $config) {

                    if (!$post->categoria) {
                        return;
                    }

                    $sitemap->add(
                        Url::create(
                            route($config['route'], [
                                'categoria' => $post->categoria->slug,
                                'slug' => $post->slug,
                            ])
                        )
                            ->setLastModificationDate(
                                $post->modificado ?? $post->criado
                            )
                            ->setPriority(0.6)
                    );
                });
        }

        return $sitemap;
    }
}
