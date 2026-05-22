<?php

namespace App\Http\Controllers\Engenharia;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;


class PoliticasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacidade() {
        return Inertia::render('Engenharia/PoliticaPrivacidade');
    }
};