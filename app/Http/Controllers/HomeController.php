<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Parsedown;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    public function showDoc($filename)
    {
        $path = base_path($filename . '.md');

        if (!File::exists($path)) {
            abort(404);
        }

        $content = File::get($path);
        $htmlContent = (new Parsedown())->text($content);

        return view('docs', ['content' => $htmlContent]);
    }
}
