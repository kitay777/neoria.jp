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
use App\Models\TimeProductApplication;






/**
 * TimeProductController handles the management of time products.
 */

class TimeProductController extends Controller
{

    public function index()
    {
        $query = TimeProduct::with(['user','category'])
            ->where('user_id', auth()->id()); // ←セミコロン1個で

        if (session()->has('category_filter_ids')) {
            $categoryIds = array_filter((array) session('category_filter_ids'));
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        $products   = $query->latest()->get();
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return view('time_products.index', compact('products','categories'));
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
    $timeProduct->load(['user', 'category']);

    $purchasedCount = 0;
    $lastPurchasedAt = null;
    $userPoints = 0;
    $hasEnoughPoints = false;

    if (auth()->check()) {
        $userPoints = (int) (auth()->user()->points ?? 0);
        $hasEnoughPoints = $userPoints >= (int) ($timeProduct->price ?? 0);

        // アプリ＝購入履歴として扱う（必要なら status='paid' などに変更）
        $apps = \App\Models\TimeProductApplication::where('time_product_id', $timeProduct->id)
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $purchasedCount  = $apps->count();
        $lastPurchasedAt = optional($apps->first())->created_at;
    }

    return view('time_products.show', [
        'timeProduct'     => $timeProduct,
        'purchasedCount'  => $purchasedCount,
        'lastPurchasedAt' => $lastPurchasedAt,
        'userPoints'      => $userPoints,
        'hasEnoughPoints' => $hasEnoughPoints,
    ]);
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
            'trade_types'   => 'required|array', 
            'trade_types.*' => 'in:in_person,online,phone,message',
            'prefecture'    => 'nullable|string|max:255',
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
            'trade_types' => $request->trade_types,  // ← 配列のままOK（castsがあるため）
            'prefecture'  => $request->prefecture,
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
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|integer|min:0',
            'duration'     => 'required|integer|in:0,15,30,60,120,180,1440,10080,43200',
            'category_id'  => 'nullable|exists:categories,id',
            'is_active'    => 'boolean',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:204800',
            'trade_types'  => 'required|array',
            'trade_types.*'=> 'in:in_person,online,phone,message',
            'prefecture'   => 'nullable|string|max:255',
        ]);

        // まずベースの更新データ
        $data = $request->only([
            'title','description','price','duration','category_id','is_active','prefecture'
        ]);
        $data['trade_types'] = $request->input('trade_types', []);

        // 画像があれば差し替え
        if ($request->hasFile('image')) {
            $original = $request->file('image');
            $manager  = new ImageManager(new Driver());
            $image    = $manager->read($original->getPathname())->cover(1024,1024)->toPng();

            $filename = Str::uuid()->toString().'.png';
            $path     = 'time-products/'.$filename;
            Storage::disk('public')->put($path, (string) $image); // save() でもOK

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

