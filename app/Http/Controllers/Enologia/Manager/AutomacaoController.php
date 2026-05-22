<?php

namespace App\Http\Controllers\Enologia\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Enologia\Processo;

class AutomacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $processos = Processo::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'processosIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($processo) {
                return [
                    'id' => $processo->id,
                    'visivel' => $processo->visivel,
                    'nome' => $processo->processosIdiomas->isNotEmpty() ? $processo->processosIdiomas[0]->nome : null
                ];
            });

        return Inertia::render('Enologia/Manager/Automacao/index', [
            'processos' => $processos,
        ]);
    }
};