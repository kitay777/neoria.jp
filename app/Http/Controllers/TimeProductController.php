<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeProduct;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * TimeProductController handles the management of time products.
 */

class TimeProductController extends Controller
{
    public function index()
    {
        $products = TimeProduct::where('user_id', auth()->id())->latest()->get();
        return view('time_products.index', compact('products'));
    }
    public function market()
    {
        $products = \App\Models\TimeProduct::with('user')
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('time_products.market', compact('products'));
    }


    public function create()
    {
        $categories = Category::all(); // すでに存在しているカテゴリを取得
        return view('time_products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|in:15,30,60',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $original = $request->file('image');

            // 画像処理
            $manager = new ImageManager(new Driver());
            $image = $manager
                ->read($original->getPathname())
                ->cover(1024, 1024)   // 中央トリミングしてリサイズ
                ->toPng();            // PNG形式で保存

            // 保存パスを決定
            $filename = 'time-products/' . Str::uuid() . '.png';
            Storage::disk('public')->put($filename, (string) $image);

            $imagePath = $filename;
        }
        TimeProduct::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'price' => $request->price,
            'duration' => $request->duration,
            'category_id' => $request->category_id, 
            'is_active' => true,
        ]);

        return redirect()->route('time-products.index')->with('success', '商品を登録しました。');
    }

}

