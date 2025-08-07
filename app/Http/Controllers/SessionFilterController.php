<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionFilterController extends Controller
{
    public function set(Request $request)
    {
        logger()->debug('Category filter session', session('category_filter_ids', []));

        if ($request->filled('clear')) {
            session()->forget('category_filter_ids');
        } elseif ($request->filled('category_id')) {
            $id = (int) $request->category_id;
            $ids = session('category_filter_ids', []);

            // 重複防止と型一致のため厳密比較を使う
            if (in_array($id, $ids, true)) {
                $ids = array_filter($ids, fn($v) => $v !== $id);
            } else {
                $ids[] = $id;
            }

            // インデックスをリセットして格納
            session(['category_filter_ids' => array_values($ids)]);
        }

        return back();
    }

}
