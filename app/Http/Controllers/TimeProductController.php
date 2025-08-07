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
        $query = TimeProduct::with(['user', 'category'])->where('is_active', true);

if (session()->has('category_filter_ids')) {
    $categoryIds = session('category_filter_ids');
    if (!empty($categoryIds)) {
        $query->whereIn('category_id', $categoryIds);
    }
}

        $products = $query->latest()->get();
        $categories = Category::with('children')->whereNull('parent_id')->get();


        return view('time_products.index', compact('products', 'categories'));
    }
    public function market()
    {
        $query = TimeProduct::with(['user', 'category'])->where('is_active', true);

if (session()->has('category_filter_ids')) {
    $categoryIds = session('category_filter_ids');
    if (!empty($categoryIds)) {
        $query->whereIn('category_id', $categoryIds);
    }
}

        $products = $query->latest()->get();
        $categories = Category::with('children')->whereNull('parent_id')->get();



        return view('time_products.market', compact('products', 'categories'));
    }



    public function create()
    {
        $categories = Category::all(); // すでに存在しているカテゴリを取得
        return view('time_products.create', compact('categories'));
    }

    public function show(\App\Models\TimeProduct $timeProduct)
    {
        $timeProduct->load(['user', 'category']); // リレーション事前ロード
        return view('time_products.show', compact('timeProduct'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:204800',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|in:0,15,30,60,120,180,1440,10080,43200',
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
    public function edit(TimeProduct $timeProduct)
    {
        $this->authorize('update', $timeProduct); // オプション
        $categories = Category::all();
        return view('time_products.edit', compact('timeProduct', 'categories'));
    }

    public function update(Request $request, TimeProduct $timeProduct)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|in:0,15,30,60,120,180,1440,10080,43200',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:204800',
        ]);
        $data = $request->only([
            'title', 'description', 'price', 'duration', 'category_id', 'is_active'
        ]);
        // 画像があれば置き換え
        if ($request->hasFile('image')) {
            $original = $request->file('image');

            $manager = new ImageManager(new Driver());

            $image = $manager
                ->read($original->getPathname())
                ->cover(1024, 1024)
                ->toPng();

            $filename = Str::uuid()->toString() . '.png';
            $path = 'time-products/' . $filename;
            $fullPath = storage_path('app/public/' . $path);
            $image->save($fullPath);

            $data['image_path'] = $path;
        }

        $timeProduct->update($data);

        return redirect()->route('time-products.index')->with('success', '商品を更新しました');
    }

    public function destroy(TimeProduct $timeProduct)
    {
        $this->authorize('delete', $timeProduct); // オプション
        $timeProduct->delete();

        return redirect()->route('time-products.index')->with('success', '商品を削除しました');
    }


}

