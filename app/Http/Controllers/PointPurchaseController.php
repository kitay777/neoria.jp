<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\User;
use App\Models\PointLog;


class PointPurchaseController extends Controller
{
    public function index()
    {
        return view('points.buy');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1000', // 100〜100000円
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => "{$amount}ポイント購入",
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('points.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('points.checkout.cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'points' => $amount,
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($request->session_id);
        $user = User::find($session->metadata->user_id);
        $points = (int)$session->metadata->points;

        if (!$user) {
            return redirect()->route('points.buy')->with('error', 'ユーザーが見つかりませんでした');
        }

        // ポイント加算
        $user->points += $points;
        $user->save();

        // ログ保存
        /* WEBHOOKに対応したのでいらない
        PointLog::create([
            'user_id' => $user->id,
            'amount' => $points,
            'type' => 'bonus',
            'description' => "Stripeで購入",
            'balance' => $user->points,
        ]);
        */

        return view('points.success', compact('points'));
    }

    public function cancel()
    {
        return view('points.cancel');
    }
}

