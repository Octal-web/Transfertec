<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Engenharia\Membro;
use App\Models\Engenharia\MembroIdioma;
use App\Models\Engenharia\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Engenharia\Manager\PostMemberRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class MembrosController extends Controller
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

        return Inertia::render('Engenharia/Manager/Membros/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostMemberRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $membro = new Membro;
            $membro_idioma = new MembroIdioma;

            $membro->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            $response = $membro->save();

            $membro_idioma->nome = $request->nome;
            $membro_idioma->cargo = $request->cargo;
            $membro_idioma->descricao = $request->descricao;

            $membro_idioma->membro_id = $membro->id;
            $membro_idioma->idioma_id = $idioma->id;

            $response = $membro_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('content/members/'), $membro->imagem);

                return to_route('Engenharia.Manager.Institucional.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Engenharia.Manager.Institucional.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $membro = Membro::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'membrosIdiomas' => function ($q) use ($idioma) {
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

        if(!$membro) {
            return Inertia::location(route('Engenharia.Manager.Institucional.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $membro = [
            'id' => $membro->id,
            'imagem' => asset('eng/content/members/' . $membro->imagem),
            'nome' => count($membro->membrosIdiomas) ? $membro->membrosIdiomas[0]->nome : null,
            'cargo' => count($membro->membrosIdiomas) ? $membro->membrosIdiomas[0]->cargo : null,
            'descricao' => count($membro->membrosIdiomas) ? $membro->membrosIdiomas[0]->descricao : null,
        ];

        return Inertia::render('Engenharia/Manager/Membros/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'membro' => $membro
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostMemberRequest $request, $id) {
        if($request->ajax()){
            $membro = Membro::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $membro_idioma = MembroIdioma::query()
                ->where([
                    'excluido' => null,
                    'membro_id' => $membro->id
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

            if (!$membro) {
                return to_route('Engenharia.Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($membro, 'membrosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Engenharia.Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Engenharia.Manager.Institucional.index'));
            }

            if (!$membro_idioma) {
                $membro_idioma = new MembroIdioma;

                $membro_idioma->membro_id = $membro->id;
                $membro_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $membroOriginal = $copier->copy($membro);
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $membro->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $membro_idioma->nome = $request->nome;
            $membro_idioma->cargo = $request->cargo;
            $membro_idioma->descricao = $request->descricao;

            $response = $membro->save();
            $response = $membro_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($membro->imagem && isset($membroOriginal) && File::exists('content/members/' . $membroOriginal->imagem)) {
                        File::delete('content/members/' . $membroOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('content/members/'), $membro->imagem);
                }

                return to_route('Engenharia.Manager.Institucional.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Engenharia.Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Membro::query()
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

            $response = Membro::query()
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
                    $registro = Membro::query()
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