<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 关注用户
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     * @author shijiacheng
     */
    public function store(User $user)
    {
        $this->authorize('follow', $user);

        // 如果未关注才关注
        if (!Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
        }

        return redirect()->route('users.show', $user->id);
    }

    /**
     * 取关
     *
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     * @author shijiacheng
     */
    public function destroy(User $user)
    {
        $this->authorize('follow', $user);

        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->unfollow($user->id);
        }

        return redirect()->route('users.show', $user->id);
    }

}
