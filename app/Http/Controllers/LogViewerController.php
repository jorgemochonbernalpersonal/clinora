<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LogViewerController extends Controller
{
    /**
     * Display the log viewer page
     */
    public function index(): View
    {
        return view('dashboard.logs.index');
    }
}
