<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->hasFile('profile_photo')) {
            $original = $request->file('profile_photo');

            $manager = new ImageManager(new Driver());
            $image = $manager->read($original->getPathname());

            $width = $image->width();
            $height = $image->height();

            if ($width > $height) {
                $image = $image->scale(height: 640);
            } else {
                $image = $image->scale(width: 640);
            }

            $image = $image->cover(640, 640)->toPng();


            $filename = 'profiles/' . Str::uuid() . '.png';
            Storage::disk('public')->put($filename, (string) $image);

            $user->profile_photo_path = $filename;
            $user->save();
        }
        /* 全体が出るようにする場合

        if ($request->hasFile('profile_photo')) {
            $original = $request->file('profile_photo');

            $manager = new ImageManager(new Driver());
            $image = $manager->read($original->getPathname());

            // アスペクト比を保って 640px以内に収める
            $image = $image->scaleDown(640, 640);

            // 640×640の白キャンバスを作成
            $canvas = $manager->create(640, 640)->fill('white');

            // 中央に合成（上下左右に余白ができる）
            $image = $canvas->place($image, 'center')->toPng();

            $filename = 'profiles/' . Str::uuid() . '.png';
            Storage::disk('public')->put($filename, (string) $image);

            $user->profile_photo_path = $filename;
            $user->save();
        }
        */

        // 通常のフォーム項目を反映
        $user->fill($request->validated());

        // メール変更があった場合の処理
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
