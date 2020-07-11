<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest', [
           'only' => ['create']
        ]);
    }

    /**
     * 登录页
     *
     * @return Application|Factory|View
     * @author shijiacheng
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * 登录
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     * @author shijiacheng
     */
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
           'email' => 'required|email|max:80',
           'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            // 是否验证邮箱
            if (Auth::user()->activated) {
                session()->flash('success', '欢迎回来');
                $fallback = route('users.show', Auth::user());
                return redirect()->intended($fallback);
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return  redirect('/');
            }

        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    /**
     * 退出登录
     *
     * @return Application|RedirectResponse|Redirector
     * @author shijiacheng
     */
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }


}
