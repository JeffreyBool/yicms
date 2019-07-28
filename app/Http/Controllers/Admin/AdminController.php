<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdminRequest;
use Illuminate\Http\Request;
use App\Services\AdminsService;
use App\Repositories\RolesRepository;
use App\Http\Requests\Admin\AdminLoginRequest;

class AdminController extends Controller {
    protected $adminsService;

    protected $rolesRepository;

    /**
     * AdminController constructor.
     * @param AdminsService $adminsService
     * @param RolesRepository $rolesRepository
     */
    public function __construct(AdminsService $adminsService, RolesRepository $rolesRepository)
    {
        $this->adminsService = $adminsService;

        $this->rolesRepository = $rolesRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $admins = $this->adminsService->getAdminsWithRoles();

        return $this->view(null, compact('admins'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $roles = $this->rolesRepository->getRoles();

        return view('admin.admins.create', compact('roles'));
    }

    /**
     * @param AdminRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminRequest $request)
    {
        $this->adminsService->create($request);

        flash('添加管理员成功')->success()->important();

        return redirect()->route('admins.index');
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $admin = $this->adminsService->ById($id);

        $roles = $this->rolesRepository->getRoles();

        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    /**
     * @param AdminRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminRequest $request, $id)
    {
        $this->adminsService->update($request, $id);

        flash('更新资料成功')->success()->important();

        return redirect()->route('admins.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $admin = $this->adminsService->ById($id);

        if (empty($admin)) {
            flash('删除失败')->error()->important();

            return redirect()->route('admins.index');
        }


        $admin->roles()->detach();

        $admin->delete();


        flash('删除成功')->success()->important();

        return redirect()->route('admins.index');
    }

    /**
     * @param $status
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status($status, $id)
    {
        $admin = $this->adminsService->ById($id);

        if (empty($admin)) {
            flash('操作失败')->error()->important();

            return redirect()->route('admins.index');
        }

        $admin->update(['status' => $status]);

        flash('更新状态成功')->success()->important();

        return redirect()->route('admins.index');
    }

    public function showLoginForm()
    {
        return view('admin.admins.login');
    }

    /**
     * 管理员登陆
     * @param AdminLoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function loginHandle(AdminLoginRequest $request)
    {
        $result = $this->adminsService->login($request);

        if ( !$result) {
            return viewError('登录失败', 'login');
        }

        return viewError('登录成功!', 'index.index', 'success');
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $this->adminsService->logout();

        return redirect()->route('login');
    }
}
