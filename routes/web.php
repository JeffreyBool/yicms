<?php

/**
 * 后台路由
 */

/**后台模块**/
Route::group(['namespace' => 'Admin','prefix' => 'admin'], function (){

    Route::get('login','AdminController@showLoginForm')->name('login');  //后台登陆页面

    Route::post('login-handle','AdminController@loginHandle')->name('login-handle'); //后台登陆逻辑

    Route::get('logout','AdminController@logout')->name('admin.logout'); //退出登录

    /**需要登录认证模块**/
    Route::middleware(['auth:admin','rbac'])->group(function (){

        Route::resource('index', 'PageController', ['only' => ['index']]);  //首页

        Route::get('index/main', 'PageController@main')->name('index.main'); //首页数据分析

        Route::get('admins/status/{statis}/{admin}','AdminController@status')->name('admins.status');

        Route::get('admins/delete/{admin}','AdminController@delete')->name('admins.delete');

        Route::resource('admins','AdminController',['only' => ['index', 'create', 'store', 'update', 'edit']]); //管理员

        Route::get('roles/access/{role}','RoleController@access')->name('roles.access');

        Route::post('roles/group-access/{role}','RoleController@groupAccess')->name('roles.group-access');

        Route::resource('roles','RoleController',['only'=>['index','create','store','update','edit','destroy'] ]);  //角色

        Route::get('rules/status/{status}/{rules}','RuleController@status')->name('rules.status');

        Route::resource('rules','RuleController',['only'=> ['index','create','store','update','edit','destroy'] ]);  //权限

        Route::resource('actions','ActionController',['only'=> ['index','destroy'] ]);  //日志
    });
});
