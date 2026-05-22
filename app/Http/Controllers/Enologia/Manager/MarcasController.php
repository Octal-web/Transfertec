<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;
use App\Models\Enologia\Marca;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Enologia\Manager\PostBrandRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class MarcasController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar() {
        return Inertia::render('Enologia/Manager/Marcas/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostBrandRequest $request) {
        if($request->ajax()){            
            $cliente = new Marca;

            $cliente->logo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            $cliente->nome = $request->nome;
            $cliente->link = $request->link;

            $response = $cliente->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('eno/content/brands/thumbs/'), $cliente->logo);

                return to_route('Enologia.Manager.Insumos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Enologia.Manager.Insumos.index'));
        }

        $cliente = Marca::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->first();

        if(!$cliente) {
            return Inertia::location(route('Enologia.Manager.Insumos.index'));
        }

        $cliente = [
            'id' => $cliente->id,
            'logo' => asset('eno/content/brands/thumbs/' . $cliente->logo),
            'nome' => $cliente->nome,
            'link' => $cliente->link,
        ];

        return Inertia::render('Enologia/Manager/Marcas/editar', [
            'cliente' => $cliente
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostBrandRequest $request, $id) {
        if($request->ajax()){
            $cliente = Marca::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            if (!$cliente) {
                return to_route('Enologia.Manager.Insumos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $cliente->logo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $cliente->nome = $request->nome;
            $cliente->link = $request->link;

            $response = $cliente->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($cliente->logo && isset($clienteOriginal) && File::exists('eno/content/brands/thumbs/' . $clienteOriginal->logo)) {
                        File::delete('eno/content/brands/thumbs/' . $clienteOriginal->logo);
                    }

                    $image = $request->file('img')->move(public_path('eno/content/brands/thumbs/'), $cliente->logo);
                }

                return to_route('Enologia.Manager.Insumos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Enologia.Manager.Insumos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Marca::query()
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

            $response = Marca::query()
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
                    $registro = Marca::query()
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