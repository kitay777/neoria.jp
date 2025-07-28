<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\User;
use App\Models\PointLog;



class StripeWebhookController extends Controller
{
public function handleWebhook(Request $request)
{
    $payload = $request->getContent();
    $sig = $request->header('Stripe-Signature');

    try {
        $event = \Stripe\Webhook::constructEvent($payload, $sig, config('services.stripe.webhook_secret'));

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $userId = $session->metadata->user_id ?? null;
            $points = (int)($session->metadata->points ?? 0);

            if ($userId && $points > 0) {
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $user->points += $points;
                    $user->save();

                    \App\Models\PointLog::create([
                        'user_id' => $user->id,
                        'amount' => $points,
                        'type' => 'bonus',
                        'description' => 'Stripe決済完了',
                        'balance' => $user->points,
                    ]);
                }
            }
        }
    } catch (\Exception $e) {
        \Log::error('Stripe Webhook Error: ' . $e->getMessage());
        return response('Invalid', 400);
    }

    return response('OK', 200);
}


}

