<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 发布微博
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @author shijiacheng
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }

    /**
     * 删除微博
     * @param Status $status
     * @return RedirectResponse
     * @throws AuthorizationException
     * @author shijiacheng
     */
    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被删除成功');
        return redirect()->back();
    }

}
