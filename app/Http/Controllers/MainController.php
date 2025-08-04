<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','riyou','privacy','unei']);
    }

    public function index()
    {
        $works = Work::latest()->get();
        return view('dashboard', compact('works'));
    }
    public function riyou()
    {
        $works = Work::latest()->get();
        return view('riyou', compact('works'));
    }
    public function privacy()
    {
        $works = Work::latest()->get();
        return view('privacy', compact('works'));
    }
    public function unei()
    {
        $works = Work::latest()->get();
        return view('unei', compact('works'));
    }
}
