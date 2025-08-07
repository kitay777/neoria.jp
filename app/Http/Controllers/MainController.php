<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use App\Models\TimeProduct;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

/**
 * MainController handles the main application logic.
 */


class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','riyou','privacy','unei']);
    }

    public function index()
    {
        $query = Work::with('category');

        $userCategoryIds = collect();
        $sessionCategoryIds = collect();

        if (auth()->check()) {
            $userCategoryIds = auth()->user()->preferredCategories->pluck('id');
        }

        if (session()->has('category_filter_ids')) {
            $categoryIds = session('category_filter_ids');
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        $allCategoryIds = $userCategoryIds->merge($sessionCategoryIds)->unique();

        if ($allCategoryIds->isNotEmpty()) {
            // ✅ カテゴリが選択されているときは、そのカテゴリのみ
            $query->whereIn('category_id', $allCategoryIds);
        } else {
            // ✅ カテゴリが選択されていないときは、全件 or 状態で制限（任意）
            //$query->where('status', 'open'); ← 必要なら残す
        }

        $works = $query->latest()->get();
        $categories = Category::with('children')->whereNull('parent_id')->get();



        return view('dashboard', compact('works', 'categories'));
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
