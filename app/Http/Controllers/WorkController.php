<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;



class WorkController extends Controller
{
    //
    public function index()
    {
        $works = Work::where('user_id', Auth::id())->with('category')->latest()->paginate(10);
        return view('works.index', compact('works'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('works.create', compact('categories')); 
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|integer|min:0',
            'deadline' => 'required|date|after_or_equal:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'execution_date' => 'nullable|date|after_or_equal:today',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $original = $request->file('image');

            $manager = new ImageManager(new Driver());

            $image = $manager
                ->read($original->getPathname())  // ファイル読み込み
                ->cover(1024, 1024)               // 中央トリミング & リサイズ
                ->toPng();                        // PNGバイナリへ変換

            $filename = 'works/' . Str::uuid() . '.png';
            Storage::disk('public')->put($filename, (string) $image);

            $imagePath = $filename;
        }

        Work::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'price' => $request->price,
            'deadline' => $request->deadline,
            'is_overseas_allowed' => $request->has('is_overseas_allowed'),
            'is_verified_by_client' => $request->has('is_verified_by_client'),
            'status' => 'open',
            'execution_date' => $request->execution_date,
        ]);

        return redirect()->route('works.index')->with('success', '仕事を登録しました');

    }
    
    public function show($id){
        $work = Work::with('user', 'category')->findOrFail($id);
        return view('works.show', compact('work'));
    }
}
