<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        $works = Work::latest()->get();
        return view('dashboard', compact('works'));
    }
}
