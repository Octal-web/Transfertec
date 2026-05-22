<?php

namespace App\Http\Controllers\Engenharia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Engenharia\Certificacao;
use App\Models\Engenharia\CertificacaoIdioma;
use App\Models\Engenharia\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Engenharia\Manager\PostCertificationRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class CertificacoesController extends Controller
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

        return Inertia::render('Engenharia/Manager/Certificacoes/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostCertificationRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $certificacao = new Certificacao;
            $certificacao_idioma = new CertificacaoIdioma;

            $certificacao->logo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            $response = $certificacao->save();

            $certificacao_idioma->nome = $request->nome;

            $certificacao_idioma->certificacao_id = $certificacao->id;
            $certificacao_idioma->idioma_id = $idioma->id;

            $response = $certificacao_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('content/certifications/thumbs/'), $certificacao->logo);

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

        $certificacao = Certificacao::query()
            ->where([
                'excluido' => null,
                'certificacao_id' => $certificacao->id
            ])
            ->with([
                'certificacoesIdiomas' => function ($q) use ($idioma) {
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

        if(!$certificacao) {
            return Inertia::location(route('Engenharia.Manager.Institucional.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $certificacao = [
            'id' => $certificacao->id,
            'logo' => asset('eng/content/certifications/thumbs/' . $certificacao->logo),
            'nome' => count($certificacao->certificacoesIdiomas) ? $certificacao->certificacoesIdiomas[0]->nome : null,
            'cargo' => count($certificacao->certificacoesIdiomas) ? $certificacao->certificacoesIdiomas[0]->cargo : null,
            'descricao' => count($certificacao->certificacoesIdiomas) ? $certificacao->certificacoesIdiomas[0]->descricao : null,
        ];

        return Inertia::render('Engenharia/Manager/Certificacoes/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'certificacao' => $certificacao
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostCertificationRequest $request, $id) {
        if($request->ajax()){
            $certificacao = Certificacao::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $certificacao_idioma = CertificacaoIdioma::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
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

            if (!$certificacao) {
                return to_route('Engenharia.Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($certificacao, 'certificacoesIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Engenharia.Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Engenharia.Manager.Institucional.index'));
            }

            if (!$certificacao_idioma) {
                $certificacao_idioma = new CertificacaoIdioma;

                $certificacao_idioma->certificacao_id = $certificacao->id;
                $certificacao_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $certificacaoOriginal = $copier->copy($certificacao);
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $certificacao->logo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $certificacao_idioma->nome = $request->nome;

            $response = $certificacao->save();
            $response = $certificacao_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($certificacao->logo && isset($certificacaoOriginal) && File::exists('content/certifications/thumbs/' . $certificacaoOriginal->logo)) {
                        File::delete('content/certifications/thumbs/' . $certificacaoOriginal->logo);
                    }

                    $image = $request->file('img')->move(public_path('content/certifications/thumbs/'), $certificacao->logo);
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

            $exclusao = Certificacao::query()
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

            $response = Certificacao::query()
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
                    $registro = Certificacao::query()
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