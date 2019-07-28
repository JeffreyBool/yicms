<?php

namespace App\Http\Controllers\Admin;

use Route;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * 自动获取对应的模块名称和
     * @param null  $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        $currentAction = $this->getCurrentAction();
        /**获取当前模块名称**/
        $module = $this->getModule();
        $controller = $currentAction['controller'];
        $action = $view ? $view : $currentAction['action'];
        $view_path = "{$module}.{$controller}.{$action}";

        return view($view_path, $data, $mergeData);
    }

    /**
     * 获取当前控制器名称
     * @return mixed
     */
    public function getControllerName()
    {
        return $this->getCurrentAction()['controller'];
    }

    /**
     * 获取当前方法名称(小写)
     * @return mixed
     */
    public function getActionName()
    {
        return $this->getCurrentAction()['action'];
    }

    /**
     * 获取当前控制器与方法(小写)
     * @return array
     */
    public function getCurrentAction()
    {
        $action = Route::currentRouteName();
        list($controller, $action) = explode('.', $action);
        return ['controller' => $controller, 'action' => $action];
    }

    /**
     * 获取当前模块名称(小写)
     * @return mixed
     */
    public function getModule()
    {
        $module = Request()->route()->action['prefix'];
        $module = explode('/', $module);
        return $module[1];
    }
}
