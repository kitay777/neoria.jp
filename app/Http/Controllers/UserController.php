<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function setCategoryPreference(Request $request)
    {
        $user = auth()->user();

        // カテゴリをすべてクリア
        if ($request->filled('clear')) {
            $user->preferredCategories()->detach();
        }

        // 単一カテゴリトグル動作（選択 → 削除、非選択 → 追加）
        elseif ($request->filled('category_id')) {
            $categoryId = (int) $request->category_id;

            if ($user->preferredCategories->contains($categoryId)) {
                $user->preferredCategories()->detach($categoryId);
            } else {
                $user->preferredCategories()->attach($categoryId);
            }
        }

        return back();
    }

}
