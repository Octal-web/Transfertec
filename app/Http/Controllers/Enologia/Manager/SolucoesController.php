<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\Solucao;
use App\Models\Enologia\SolucaoIdioma;
use App\Models\Enologia\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Enologia\Manager\PostSolutionRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class SolucoesController extends Controller
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

        return Inertia::render('Enologia/Manager/Solucoes/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostSolutionRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $solucao = new Solucao;
            $solucao_idioma = new SolucaoIdioma;

            $solucao->icone = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            $response = $solucao->save();

            $solucao_idioma->titulo = $request->titulo;
            $solucao_idioma->descricao = $request->descricao;

            $solucao_idioma->solucao_id = $solucao->id;
            $solucao_idioma->idioma_id = $idioma->id;

            $response = $solucao_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eno/content/solutions/thumbs/'), $solucao->icone);

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

        $solucao = Solucao::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'solucoesIdiomas' => function ($q) use ($idioma) {
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

        if(!$solucao) {
            return Inertia::location(route('Enologia.Manager.Home.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $solucao = [
            'id' => $solucao->id,
            'icone' => asset('eno/content/solutions/thumbs/' . $solucao->icone),
            'titulo' => count($solucao->solucoesIdiomas) ? $solucao->solucoesIdiomas[0]->titulo : null,
            'descricao' => count($solucao->solucoesIdiomas) ? $solucao->solucoesIdiomas[0]->descricao : null
        ];

        return Inertia::render('Enologia/Manager/Solucoes/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'solucao' => $solucao
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostSolutionRequest $request, $id) {
        if($request->ajax()){
            $solucao = Solucao::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $solucao_idioma = SolucaoIdioma::query()
                ->where([
                    'excluido' => null,
                    'solucao_id' => $solucao->id
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

            if (!$solucao) {
                return to_route('Enologia.Manager.Home.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($solucao, 'solucoesIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Enologia.Manager.Home.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Enologia.Manager.Home.index'));
            }

            if (!$solucao_idioma) {
                $solucao_idioma = new SolucaoIdioma;

                $solucao_idioma->solucao_id = $solucao->id;
                $solucao_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $solucaoOriginal = $copier->copy($solucao);
            }

            if ($request->file('img') && $request->file('img')->isValid()) {
                $solucao->icone = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $solucao_idioma->titulo = $request->titulo;
            $solucao_idioma->descricao = $request->descricao;

            $response = $solucao->save();
            $response = $solucao_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($solucao->icone && isset($solucaoOriginal) && File::exists('eno/content/solutions/thumbs/' . $solucaoOriginal->icone)) {
                        File::delete('eno/content/solutions/thumbs/' . $solucaoOriginal->icone);
                    }

                    $image = $request->file('img')->move(public_path('eno/content/solutions/thumbs/'), $solucao->icone);
                }

                return to_route('Enologia.Manager.Home.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Enologia.Manager.Home.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Solucao::query()
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

            $response = Solucao::query()
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
                    $registro = Solucao::query()
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