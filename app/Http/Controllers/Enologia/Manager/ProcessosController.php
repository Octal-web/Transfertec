<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\Processo;
use App\Models\Enologia\ProcessoIdioma;
use App\Models\Enologia\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Enologia\Manager\PostProcessRequest;

use Carbon\Carbon;

class ProcessosController extends Controller
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

        return Inertia::render('Enologia/Manager/Processos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostProcessRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $processo = new Processo;
            $processo_idioma = new ProcessoIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (Processo::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $processo->slug = $slug;
            
            $response = $processo->save();

            $processo_idioma->nome = $request->nome;

            $processo_idioma->processo_id = $processo->id;
            $processo_idioma->idioma_id = $idioma->id;

            $response = $processo_idioma->save();

            if ($response) {
                return to_route('Enologia.Manager.Automacao.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Enologia.Manager.Automacao.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $processo = Processo::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'processosIdiomas' => function ($q) use ($idioma) {
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

        if(!$processo) {
            return Inertia::location(route('Enologia.Manager.Automacao.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $processo = [
            'id' => $processo->id,
            'layout' => $processo->layout,
            'nome' => count($processo->processosIdiomas) ? $processo->processosIdiomas[0]->nome : null,
            'utilidade' => count($processo->processosIdiomas) ? $processo->processosIdiomas[0]->utilidade : null,
            'descricao' => count($processo->processosIdiomas) ? $processo->processosIdiomas[0]->descricao : null,
            'detalhes' => count($processo->processosIdiomas) ? $processo->processosIdiomas[0]->detalhes : null,
        ];

        return Inertia::render('Enologia/Manager/Processos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'processo' => $processo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostProcessRequest $request, $id) {
        if($request->ajax()){
            $processo = Processo::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $processo_idioma = ProcessoIdioma::query()
                ->where([
                    'excluido' => null,
                    'processo_id' => $processo->id
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

            if (!$processo) {
                return to_route('Enologia.Manager.Automacao.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($processo, 'processosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Enologia.Manager.Automacao.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Enologia.Manager.Automacao.index'));
            }

            if (!$processo_idioma) {
                $processo_idioma = new ProcessoIdioma;

                $processo_idioma->processo_id = $processo->id;
                $processo_idioma->idioma_id = $idioma;
            }

            $slug = $processo->slug;
            $processo->layout = $request->layout;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $processo_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (Processo::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $processo->slug = $slug;

            $processo_idioma->nome = $request->nome;
            $processo_idioma->utilidade = $request->utilidade;
            $processo_idioma->descricao = $request->descricao;
            $processo_idioma->detalhes = $request->detalhes;

            $response = $processo->save();
            $response = $processo_idioma->save();

            if ($response) {
                return to_route('Enologia.Manager.Automacao.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Enologia.Manager.Automacao.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Processo::query()
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

            $response = Processo::query()
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
                    $registro = Processo::query()
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