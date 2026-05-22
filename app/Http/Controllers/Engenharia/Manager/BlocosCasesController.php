<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Engenharia\CaseCliente;
use App\Models\Engenharia\Bloco;
use App\Models\Engenharia\BlocoIdioma;
use App\Models\Engenharia\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Engenharia\Manager\PostCaseBlockRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class BlocosCasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id) {
        if (!$id) {
            return Inertia::location(route('Engenharia.Manager.Home.index'));
        }

        $caseCliente = CaseCliente::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->with([
                'casesClientesIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'blocos' => function ($q)  {
                    $q->where([
                        'excluido' => null
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC');
                },
            ])
            ->first();

        if(!$caseCliente) {
            return Inertia::location(route('Engenharia.Manager.Home.index'));
        }

        $caseClienteData = [
            'id' => $caseCliente->id,
            'nome' => count($caseCliente->casesClientesIdiomas) ? $caseCliente->casesClientesIdiomas[0]->nome : null,
            'blocos' => $caseCliente->blocos->map(function ($bloco) {
                return [
                    'id' => $bloco->id,
                    'visivel' => $bloco->visivel ? true : false,
                    'imagem' => asset('eng/content/cases/content/' . $bloco->imagem),
                ];
            })->values()->all(),
        ];

        return Inertia::render('Engenharia/Manager/Cases/Blocos/index', [
            'caseCliente' => $caseClienteData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar($id) {
        if (!$id) {
            return Inertia::location(route('Engenharia.Manager.Home.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        return Inertia::render('Engenharia/Manager/Cases/Blocos/adicionar', [
            'id' => $id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostCaseBlockRequest $request, $id) {
        if (!$id) {
            return Inertia::location(route('Engenharia.Manager.Home.index'));
        }
        
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $bloco = new Bloco;
            $bloco_idioma = new BlocoIdioma;

            $bloco->case_id = $id;
            $bloco->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            $response = $bloco->save();

            $bloco_idioma->texto = $request->texto;

            $bloco_idioma->bloco_id = $bloco->id;
            $bloco_idioma->idioma_id = $idioma->id;

            $response = $bloco_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eng/content/cases/content/'), $bloco->imagem);

                return to_route('Engenharia.Manager.Cases.Blocos.index', ['id' => $id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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

        $bloco = Bloco::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'blocosIdiomas' => function ($q) use ($idioma) {
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

        if(!$bloco) {
            return Inertia::location(route('Engenharia.Manager.Home.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $bloco = [
            'id' => $bloco->id,
            'imagem' => asset('eng/content/cases/content/' . $bloco->imagem),
            'texto' => count($bloco->blocosIdiomas) ? $bloco->blocosIdiomas[0]->texto : null,
        ];

        return Inertia::render('Engenharia/Manager/Cases/Blocos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'bloco' => $bloco,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostCaseBlockRequest $request, $id) {
        if($request->ajax()){
            $bloco = Bloco::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $bloco_idioma = BlocoIdioma::query()
                ->where([
                    'excluido' => null,
                    'bloco_id' => $bloco->id
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

            if (!$bloco) {
                return to_route('Engenharia.Manager.Home.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($bloco, 'blocosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Engenharia.Manager.Home.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Engenharia.Manager.Home.index'));
            }

            if (!$bloco_idioma) {
                $bloco_idioma = new BlocoIdioma;

                $bloco_idioma->bloco_id = $bloco->id;
                $bloco_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $blocoOriginal = $copier->copy($bloco);
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $bloco->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $bloco_idioma->texto = $request->texto;

            $response = $bloco->save();
            $response = $bloco_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($bloco->imagem && isset($blocoOriginal) && File::exists('eng/content/cases/content/' . $blocoOriginal->imagem)) {
                        File::delete('eng/content/cases/content/' . $blocoOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('eng/content/cases/content/'), $bloco->imagem);
                }

                return to_route('Engenharia.Manager.Cases.Blocos.index', ['id' => $bloco->case_id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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

            $exclusao = Bloco::query()
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

            $response = Bloco::query()
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
                    $registro = Bloco::query()
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