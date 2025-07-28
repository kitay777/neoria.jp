<?php

// app/Http/Controllers/StripeTestController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;

class StripeTestController extends Controller
{
    public function test()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Stripe APIが正しく使えるか確認
        $account = \Stripe\Account::retrieve();

        dd('Stripe連携成功: ' . $account->id);
    }
}

