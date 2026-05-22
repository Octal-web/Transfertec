<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;

use Inertia\Inertia;

use Illuminate\Support\Str;

abstract class Controller
{
    public function __construct() {
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('Controller@', $controllerAction);

        if (app('request')->route()->getPrefix() == 'engenharia/manager' || app('request')->route()->getPrefix() == 'enologia/manager') {
            $idioma = request('lang', -1);

            $idiomas = $this->getSiteManagerModel('Idioma')::all();
    
            $idioma = $this->getSiteManagerModel('Idioma')::query()
                ->where(function ($query) use ($idioma) {
                    $query->orWhere([
                        'padrao' => true
                    ])
                    ->orWhere([
                        'codigo' => $idioma
                    ]);
                })
                ->orderBy('padrao', 'ASC')
                ->orderBy('id', 'DESC')
                ->first();

            $pagina = $this->getSiteManagerModel('Pagina')::query()
                ->where([
                    'controladora' => $controller,
                    'acao' => $action
                ])
                ->with([
                    'paginasIdiomas' => function($q) use ($idioma) {
                        $q->whereHas('idiomas', function($r) use ($idioma) {
                            $r->where([
                                'id' => $idioma->id,
                            ]);
                        })
                        ->with('idiomas');
                    },
                ])
                ->first();

            $currentSite = app('request')->route()->getPrefix();

            $folders = [
                'engenharia/manager' => 'eng',
                'enologia/manager' => 'eno'
            ];

            $conteudos = $this->getSiteManagerModel('Conteudo')::query()
                ->where([
                    'controladora' => $controller,
                    'acao' => $action
                ])
                ->with([
                    'conteudosIdiomas' => function($q) use ($idioma) {
                        $q->whereHas('idiomas', function($r) use ($idioma) {
                            $r->where([
                                'id' => $idioma->id,
                            ]);
                        })
                        ->with('idiomas');
                    },
                    'parametro'
                ])
                ->get()
                ->map(function($conteudo) use ($currentSite, $folders) {
                    return [
                        'id' => $conteudo->id,
                        'bloco' => $conteudo->parametro->descricao,
                        'titulo' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->titulo : null,
                        'habilitar_titulo' => $conteudo->parametro->habilitar_titulo ? true : false,
                        'subtitulo' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->subtitulo : null,
                        'habilitar_subtitulo' => $conteudo->parametro->habilitar_subtitulo ? true : false,
                        'texto' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->texto : null,
                        'habilitar_texto' => $conteudo->parametro->habilitar_texto ? true : false,
                        'texto_formatado' => $conteudo->parametro->texto_formatado ? true : false,
                        'imagem' => asset($folders[$currentSite] . '/content/display/' . $conteudo->imagem),
                        'habilitar_img' => $conteudo->parametro->habilitar_img ? true : false,
                        'largura_img' => $conteudo->parametro->largura_img,
                        'altura_img' => $conteudo->parametro->altura_img,
                        'recortar_img' => $conteudo->parametro->recortar_img ? true : false,
                        'imagem_mobile' => asset($folders[$currentSite] . 'content/display/' . $conteudo->imagem_mobile),
                        'habilitar_img_mobile' => $conteudo->parametro->habilitar_img_mobile ? true : false,
                        'largura_img_mobile' => $conteudo->parametro->largura_img_mobile,
                        'altura_img_mobile' => $conteudo->parametro->altura_img_mobile,
                        'recortar_img_mobile' => $conteudo->parametro->recortar_img_mobile ? true : false,
                        'link' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->link : null,
                        'habilitar_link' => $conteudo->parametro->habilitar_link ? true : false,
                        'video' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->video : null,
                        'habilitar_video' => $conteudo->parametro->habilitar_video ? true : false,
                        'nova_aba' => count($conteudo->conteudosIdiomas) && $conteudo->conteudosIdiomas[0]->nova_aba ? true : false,
                        'minimizavel' => $conteudo->parametro->minimizavel ? true : false,
                        'galeria' => $conteudo->parametro->galeria ? true : false,
                    ];
                });
            
            if ($pagina) {
                $pagina = [
                    'id' => $pagina->id,
                    'titulo' => count($pagina->paginasIdiomas) ? $pagina->paginasIdiomas[0]->titulo : null,
                    'descricao' => count($pagina->paginasIdiomas) ? $pagina->paginasIdiomas[0]->descricao : null,
                    'titulo_compartilhamento' => count($pagina->paginasIdiomas) ? $pagina->paginasIdiomas[0]->titulo_compartilhamento : null,
                    'descricao_compartilhamento' => count($pagina->paginasIdiomas) ? $pagina->paginasIdiomas[0]->descricao_compartilhamento : null,
                    'imagem' => asset($folders[$currentSite] . '/content/pages/' . $pagina->imagem),
                ];
            }

            $idiomas = $this->getSiteManagerModel('Idioma')::all()->map(function($linguagem) {
                return [
                    'nome' => $linguagem->nome,
                    'codigo' => $linguagem->codigo,
                    'padrao' => $linguagem->padrao ? true : false,
                ];
            });

            Inertia::share([
                'pagina' => $pagina,
                'conteudos' => $conteudos,
                'idioma' => $idioma,
                'idiomas' => $idiomas,
                'controller' => $controller,
                'action' => $action
            ]);
        } else {
            $idiomas = $this->getSiteModel('Idioma')::query()
                ->orderBy('padrao', 'DESC')
                ->orderBy('id', 'DESC')
                ->get();

            $idioma = App::getLocale();

            $currentSite = app('request')->route()->getPrefix();

            if ($idioma == 'en') {
                $folders = [
                    'en/engenharia' => 'eng',
                    'en/enologia' => 'eno',
                    '/en' => 'eng',
                ];
            } else {
                $folders = [
                    '/engenharia' => 'eng',
                    '/enologia' => 'eno',
                    '' => 'eng',
                ];
            }
            
            if (!isset($folders[$currentSite])) {
                return null;
            }

            $conteudos = $this->getSiteModel('Conteudo')::query()
                ->where([
                    'excluido' => NULL,
                    'controladora' => $controller,
                    'acao' => $action
                ])
                ->with([
                    'conteudosIdiomas' => function ($q) use ($idioma) {
                        $q->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    },
                ])
                ->get()
                ->map(function($conteudo) use ($currentSite, $folders) {
                    return [
                        'id' => $conteudo->id,
                        'titulo' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->titulo : null,
                        'subtitulo' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->subtitulo : null,
                        'texto' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->texto : null,
                        'imagem' => asset($folders[$currentSite] . '/content/display/' . $conteudo->imagem),
                        'imagem_mobile' => asset($folders[$currentSite] . '/content/display/' . $conteudo->imagem_mobile),
                        'link' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->link : null,
                        'video' => count($conteudo->conteudosIdiomas) ? $conteudo->conteudosIdiomas[0]->video : null,
                    ];
                });

            $pagina = $this->getSiteModel('Pagina')::query()
                ->where([
                    'controladora' => $controller,
                    'acao' => $action
                ])
                ->with([
                    'paginasIdiomas' => function ($q) use ($idioma) {
                        $q->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    },
                ])
                ->first();
            
            // $dados_gerais = DadosGerais::first();

            $notifyCookie = array_key_exists('notify-cookies', $_COOKIE) ? true : false;

            if ($currentSite == 'en/engenharia' || $currentSite == '/engenharia') {
                $produtosMenu = $this->getSiteModel('Produto')::query()
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
                        },
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->get()
                    ->map(function($produto) {
                        return [
                            'id' => $produto->id,
                            'nome' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->nome : null,
                            'slug' => $produto->slug,
                        ];
                    });

                $postsMenu = $this->getSiteModel('PostCategoria')::query()
                    ->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'postsCategoriasIdiomas' => function ($q) use ($idioma) {
                            $q->whereHas('idiomas', function ($r) use ($idioma) {
                                $r->where('codigo', $idioma)
                                ->orWhere('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        },
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->get()
                    ->map(function($produto) {
                        return [
                            'id' => $produto->id,
                            'nome' => count($produto->postsCategoriasIdiomas) ? $produto->postsCategoriasIdiomas[0]->nome : null,
                            'slug' => $produto->slug,
                        ];
                    });
                } else {
                    $produtosMenu = null;
                    $postsMenu = null;
                }

            if ($pagina) {
                list($width, $height, $type, $attr) = getimagesize(public_path($folders[$currentSite] . '/content/pages/' . $pagina->imagem));
            }

            Inertia::share([
                'pagina' => [
                    'titulo' => $pagina->paginasIdiomas[0]->titulo,
                    'descricao' => $pagina->paginasIdiomas[0]->descricao,
                    'tituloCompartilhamento' => $pagina->paginasIdiomas[0]->titulo_compartilhamento,
                    'descricaoCompartilhamento' => $pagina->paginasIdiomas[0]->descricao_compartilhamento,
                    'imagem' => [
                        'endereco' => $folders[$currentSite] . '/content/pages/' . $pagina->imagem,
                        'tipo' => image_type_to_mime_type($type),
                        'largura' => $width,
                        'altura' => $height,
                    ],
                ],
                // 'dados_gerais' => $dados_gerais,
                'notifyCookie' => $notifyCookie,
                'controller' => $controller,
                'action' => $action,
                'conteudos' => $conteudos,
                'idiomas' => $idiomas,
                'idioma' => $idioma,
                'produtosMenu' => $produtosMenu,
                'postsMenu' => $postsMenu
            ]);
        }
    }

    /**
     * Retorna o model adequado baseado no site atual
     * 
     * @param string $modelName Nome base do model (ex: "Idioma", "Curso", etc)
     * @return string|null Nome completo da classe
     */
    function getSiteModel($modelName) {
        $idioma = App::getLocale();
        $currentSite = app('request')->route()->getPrefix();

        if ($idioma == 'en') {
            $sites = [
                'en/engenharia' => 'Engenharia',
                'en/enologia' => 'Enologia',
                '/en' => 'Engenharia',
            ];
        } else {
            $sites = [
                '/engenharia' => 'Engenharia',
                '/enologia' => 'Enologia',
                '' => 'Engenharia',
            ];
        }

        if (!isset($sites[$currentSite])) {
            return null;
        }
        
        $prefix = $sites[$currentSite];
        $className = "App\\Models\\{$prefix}\\{$modelName}";
        
        return class_exists($className) ? $className : null;
    }

    /**
     * Retorna o model adequado baseado no site atual
     * 
     * @param string $modelName Nome base do model (ex: "Idioma", "Curso", etc)
     * @return string|null Nome completo da classe
     */
    function getSiteManagerModel($modelName) {
        $currentSite = app('request')->route()->getPrefix();
        
        $sites = [
            'engenharia/manager' => 'Engenharia',
            'enologia/manager' => 'Enologia'
        ];
        
        if (!isset($sites[$currentSite])) {
            return null;
        }
        
        $prefix = $sites[$currentSite];
        $className = "App\\Models\\{$prefix}\\{$modelName}";
        
        return class_exists($className) ? $className : null;
    }

    protected function getLanguages($record, $translationModel, $language) {
        $idiomas = $this->getSiteManagerModel('Idioma')::query()
            ->orderByDesc('padrao')
            ->orderBy('codigo')
            ->pluck('id', 'codigo')
            ->toArray();

        $translationProperty = Str::snake($translationModel);

        if (!$language) {
            return reset($idiomas);
        } elseif (!$record->$translationProperty) {
            if (!array_key_exists($language, $idiomas)) {
                return false;
            }

            return $idiomas[$language];
        }

        return $record->$translationProperty[0]->idioma;
    }
}