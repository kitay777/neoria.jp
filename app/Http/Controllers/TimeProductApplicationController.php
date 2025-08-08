<?php

namespace App\Http\Controllers;

use App\Models\TimeProduct;
use App\Models\TimeProductApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimeProductApplicationController extends Controller
{
    public function store(Request $request, TimeProduct $timeProduct)
    {
        $this->middleware('auth');

        // 申請時の任意メッセージと、ポイント決済を想定
        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        // ポイント決済がある前提の簡易例
        $user = Auth::user();
        $price = (int) $timeProduct->price;

        return DB::transaction(function () use ($user, $timeProduct, $validated, $price) {
            // 残高チェック（なければバリデーションエラー返し）
            if (method_exists($user, 'points_balance') && (int)$user->points_balance < $price) {
                return back()->withErrors(['points' => 'ポイント残高が不足しています。']);
            }

            // ポイント減算（実装に合わせて）
            if (property_exists($user, 'points_balance')) {
                $user->decrement('points_balance', $price);
            }

            // 申請レコードを毎回新規作成（重複OK）
            $app = TimeProductApplication::create([
                'time_product_id' => $timeProduct->id,
                'user_id'         => $user->id,
                'status'          => 'paid', // 決済直後に paid とする例
                'message'         => $validated['message'] ?? null,
                'price_snapshot'  => $price,
                // application_uuid はモデル側で自動付与
            ]);

            return redirect()
                ->route('time-products.show', $timeProduct)
                ->with('success', "申請を受け付けました（申請ID: {$app->application_uuid}）");
        });
    }

    // 自分の申請履歴（任意）
    public function myApplications()
    {
        $apps = TimeProductApplication::with(['product.user','product.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('time_products.applications.mine', compact('apps'));
    }
}
