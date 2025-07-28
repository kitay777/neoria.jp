<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\PointLog;

class PointLogController extends Controller
{
    // app/Http/Controllers/PointLogController.php

    public function index()
    {
        $logs = PointLog::with(['application.work']) // ← work まで読む！
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('points.history', compact('logs'));
    }

}
