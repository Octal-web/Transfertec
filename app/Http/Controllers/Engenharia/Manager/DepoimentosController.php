<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Engenharia\Depoimento;
use App\Models\Engenharia\DepoimentoIdioma;
use App\Models\Engenharia\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Engenharia\Manager\PostTestimonyRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class DepoimentosController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar() {
        return Inertia::render('Engenharia/Manager/Depoimentos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostTestimonyRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $depoimento = new Depoimento;
            $depoimento_idioma = new DepoimentoIdioma;

            $depoimento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            $response = $depoimento->save();

            $depoimento_idioma->nome = $request->nome;
            $depoimento_idioma->empresa = $request->empresa;
            $depoimento_idioma->depoimento = $request->depoimento;

            $depoimento_idioma->depoimento_id = $depoimento->id;
            $depoimento_idioma->idioma_id = $idioma->id;

            $response = $depoimento_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('content/testimonies/thumbs/'), $depoimento->imagem);

                return to_route('Engenharia.Manager.Home.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Engenharia.Manager.Home.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $depoimento = Depoimento::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'depoimentosIdiomas' => function ($q) use ($idioma) {
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

        if(!$depoimento) {
            return Inertia::location(route('Engenharia.Manager.Home.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $depoimento = [
            'id' => $depoimento->id,
            'imagem' => asset('eng/content/testimonies/thumbs/' . $depoimento->imagem),
            'nome' => count($depoimento->depoimentosIdiomas) ? $depoimento->depoimentosIdiomas[0]->nome : null,
            'empresa' => count($depoimento->depoimentosIdiomas) ? $depoimento->depoimentosIdiomas[0]->empresa : null,
            'depoimento' => count($depoimento->depoimentosIdiomas) ? $depoimento->depoimentosIdiomas[0]->depoimento : null,
        ];

        return Inertia::render('Engenharia/Manager/Depoimentos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'depoimento' => $depoimento
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostTestimonyRequest $request, $id) {
        if($request->ajax()){
            $depoimento = Depoimento::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $depoimento_idioma = DepoimentoIdioma::query()
                ->where([
                    'excluido' => null,
                    'depoimento_id' => $depoimento->id
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

            if (!$depoimento) {
                return to_route('Engenharia.Manager.Home.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($depoimento, 'depoimentosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Engenharia.Manager.Home.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Engenharia.Manager.Home.index'));
            }

            if (!$depoimento_idioma) {
                $depoimento_idioma = new depoimentoIdioma;

                $depoimento_idioma->depoimento_id = $depoimento->id;
                $depoimento_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $depoimentoOriginal = $copier->copy($depoimento);
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $depoimento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $depoimento_idioma->nome = $request->nome;
            $depoimento_idioma->empresa = $request->empresa;
            $depoimento_idioma->depoimento = $request->depoimento;

            $response = $depoimento->save();
            $response = $depoimento_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($depoimento->imagem && isset($depoimentoOriginal) && File::exists('content/testimonies/thumbs/' . $depoimentoOriginal->imagem)) {
                        File::delete('content/testimonies/thumbs/' . $depoimentoOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('content/testimonies/thumbs/'), $depoimento->imagem);
                }

                return to_route('Engenharia.Manager.Home.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Engenharia.Manager.Home.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Depoimento::query()
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

            $response = Depoimento::query()
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
                    $registro = Depoimento::query()
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