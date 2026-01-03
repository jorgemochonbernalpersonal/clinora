<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index(): View
    {
        return view('landing', [
            'meta' => [
                'title' => 'Clinora - Software de Gestión para Clínicas de Salud | Prueba Gratis',
                'description' => 'Gestiona tu clínica de salud con Clinora. Software SaaS para psicólogos, fisioterapeutas y nutricionistas. Citas, teleconsulta, facturación y más. Prueba gratis 14 días.',
                'keywords' => 'software gestión clínica, software psicólogos, telemedicina, gestión pacientes, software salud',
            ],
        ]);
    }
}

