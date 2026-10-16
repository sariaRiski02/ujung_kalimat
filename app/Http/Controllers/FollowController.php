<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 422, 'Tidak dapat mengikuti diri sendiri.');

        if ($request->user()->follow($user)) {
            $user->notify(new NewFollowerNotification($request->user()));
        }

        return back();
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $request->user()->unfollow($user);

        return back();
    }

    public function removeFollower(Request $request, User $user): RedirectResponse
    {
        $user->unfollow($request->user());

        return back();
    }
}
