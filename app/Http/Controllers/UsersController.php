<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['shw', 'create', 'store', 'index']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 注册页
     *
     * @return Application|Factory|View
     * @author shijiacheng
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 用户详情页
     *
     * @param User $user
     * @return Application|Factory|View
     * @throws AuthorizationException
     * @author shijiacheng
     */
    public function show(User $user)
    {
        $this->authorize('update', $user);
        return view('users.show', compact('user'));
    }

    /**
     * 	创建用户
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * 用户资料编辑页
     *
     * @param User $user
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * 更新用户资料
     *
     * @param User $user
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException|AuthorizationException
     * @author shijiacheng
     */
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        // 赋值
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        // 更新
        $user->update($data);

        // 会话闪存消息提示
        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    /**
     * 用户列表
     *
     * @return Application|Factory|View
     * @author shijiacheng
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * 删除用户
     *
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     * @author shijiacheng
     */
    public function destroy(User $user, Request $request)
    {
        $this->authorize('destroy', $user);

        $user->delete();

        session()->flash('success', '成功删除用户！');
        // 分页，查询当前页是否存在「也许删除的是最后一页最后一条数据」
        $paginate = User::paginate(10);
        // 如果不存在页，跳转到最后一页
        if ($request->input('page') > $paginate->lastPage()) {
            return redirect()->route('users.index', ['page' => $paginate->lastPage()]);
        }

        return back();
    }

}
