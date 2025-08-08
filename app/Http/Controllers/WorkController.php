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
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\WorkCreated;
use Illuminate\Support\Facades\Log;






class WorkController extends Controller
{
     
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
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

        $work = Work::create([
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

        Log::info('Work作成 → イベント発火直前');
        event(new WorkCreated($work));
        Log::info('Work作成 → イベント発火直後');
        return redirect()->route('works.index')->with('success', '仕事を登録しました');

        }
    
    public function show(Work $work)
    {
        $userApplications = $work->applications()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(5, ['*'], 'user_page');

        $latestAll = $work->applications()
            ->with('user')
            ->latest()
            ->get()
            ->unique('user_id')
            ->values();

        $perPage = 4;
        $currentPage = LengthAwarePaginator::resolveCurrentPage('latest_page');
        $currentItems = $latestAll->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $latestPaginated = new LengthAwarePaginator(
            $currentItems,
            $latestAll->count(),
            $perPage,
            $currentPage,
            ['pageName' => 'latest_page']
        );

        // ✅ pathを明示
        $latestPaginated->withPath(route('works.show', $work));

        return view('works.show', [
            'work' => $work,
            'userApplications' => $userApplications,
            'latestApplications' => $latestPaginated,
        ]);
    }



    public function manageShow($id)
    {
        $work = Work::with(['category', 'applications.user'])
            ->where('user_id', Auth::id()) // 自分の仕事だけ見られるように制限
            ->findOrFail($id);

        return view('works.manage-show', compact('work'));
    }

}
