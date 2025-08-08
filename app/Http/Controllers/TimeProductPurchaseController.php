<?php

// app/Http/Controllers/TimeProductPurchaseController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TimeProduct;
use App\Models\TimeProductApplication;
use App\Models\PointLog;

class TimeProductPurchaseController extends Controller
{
    public function apply(Request $request, TimeProduct $timeProduct)
    {
        $user  = $request->user();
        $price = (int) ($timeProduct->price ?? 0);

        return DB::transaction(function () use ($request, $user, $timeProduct, $price) {
            // 行ロックで同時購入を防ぐ
            $lockedUser = $user->newQuery()->whereKey($user->id)->lockForUpdate()->first();

            if ($price > 0) {
                // 残高チェック
                if (($lockedUser->points ?? 0) < $price) {
                    return back()->withErrors(['points' => 'ポイントが不足しています。'])->withInput();
                }
                // 原子的に減算
                $updated = $lockedUser->newQuery()
                    ->whereKey($lockedUser->id)
                    ->where('points', '>=', $price)
                    ->update(['points' => DB::raw("points - {$price}")]);

                if ($updated !== 1) {
                    return back()->withErrors(['points' => '処理に失敗しました。もう一度お試しください。']);
                }

                $lockedUser->refresh(); // 減算後の残高反映
            }

            // 申請(=購入)レコード作成（必要に応じてカラム名調整）
            $app = TimeProductApplication::create([
                'time_product_id' => $timeProduct->id,
                'user_id'         => $lockedUser->id,
                'message'         => $request->input('message'),
                'points_spent'    => $price,
                'status'          => $price > 0 ? 'paid' : 'free',
            ]);

            // ログ記録（既存テーブル仕様に合わせる）
            PointLog::create([
                'user_id'       => $lockedUser->id,
                'application_id'=> $app->id,
                'amount'        => -$price,                     // 消費はマイナス
                'balance'       => (int) $lockedUser->points,   // 減算後残高
                'type'          => 'apply',                     // enum: apply/bonus/admin/refund
                'description'   => "TimeProduct #{$timeProduct->id} を購入",
            ]);

            return redirect()
                ->route('time-products.show', $timeProduct)
                ->with('success', '購入/申請が完了しました。');
        });
    }
}
