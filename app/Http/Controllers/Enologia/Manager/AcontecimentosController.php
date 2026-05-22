<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\Acontecimento;
use App\Models\Enologia\AcontecimentoIdioma;
use App\Models\Enologia\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Enologia\Manager\PostTimelineRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class AcontecimentosController extends Controller
{
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

        return Inertia::render('Enologia/Manager/Acontecimentos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostTimelineRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $acontecimento = new Acontecimento;
            $acontecimento_idioma = new AcontecimentoIdioma;

            $acontecimento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            $acontecimento->ano = $request->ano;

            $response = $acontecimento->save();

            $acontecimento_idioma->titulo = $request->titulo;
            $acontecimento_idioma->texto = $request->texto;

            $acontecimento_idioma->produto_id = $acontecimento->id;
            $acontecimento_idioma->idioma_id = $idioma->id;

            $response = $acontecimento_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eno/content/timeline/thumbs/'), $acontecimento->imagem);

                return to_route('Enologia.Manager.Institucional.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Enologia.Manager.Institucional.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $acontecimento = Acontecimento::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'acontecimentosIdiomas' => function ($q) use ($idioma) {
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

        if(!$acontecimento) {
            return Inertia::location(route('Enologia.Manager.Institucional.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $acontecimento = [
            'id' => $acontecimento->id,
            'ano' => $acontecimento->ano,
            'imagem' => asset('eno/content/timeline/thumbs/' . $acontecimento->imagem),
            'titulo' => count($acontecimento->acontecimentosIdiomas) ? $acontecimento->acontecimentosIdiomas[0]->titulo : null,
            'texto' => count($acontecimento->acontecimentosIdiomas) ? $acontecimento->acontecimentosIdiomas[0]->texto : null
        ];

        return Inertia::render('Enologia/Manager/Acontecimentos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'acontecimento' => $acontecimento
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostTimelineRequest $request, $id) {
        if($request->ajax()){
            $acontecimento = Acontecimento::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $acontecimento_idioma = AcontecimentoIdioma::query()
                ->where([
                    'excluido' => null,
                    'acontecimento_id' => $acontecimento->id
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

            if (!$acontecimento) {
                return to_route('Enologia.Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($acontecimento, 'institucionalIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Enologia.Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Enologia.Manager.Institucional.index'));
            }

            if (!$acontecimento_idioma) {
                $acontecimento_idioma = new AcontecimentoIdioma;

                $acontecimento_idioma->produto_id = $acontecimento->id;
                $acontecimento_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $acontecimentoOriginal = $copier->copy($acontecimento);
            }

            if ($request->file('img') && $request->file('img')->isValid()) {
                $acontecimento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $acontecimento->ano = $request->ano;

            $acontecimento_idioma->titulo = $request->titulo;
            $acontecimento_idioma->texto = $request->texto;

            $response = $acontecimento->save();
            $response = $acontecimento_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($acontecimento->imagem && isset($acontecimentoOriginal) && File::exists('eno/content/timeline/thumbs/' . $acontecimentoOriginal->imagem)) {
                        File::delete('eno/content/timeline/thumbs/' . $acontecimentoOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('eno/content/timeline/thumbs/'), $acontecimento->imagem);
                }

                return to_route('Enologia.Manager.Institucional.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Enologia.Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Acontecimento::query()
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

            $response = Acontecimento::query()
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
                    $registro = Acontecimento::query()
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