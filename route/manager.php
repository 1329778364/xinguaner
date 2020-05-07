<?php
// 手机用户相关的操作

use app\middleware\checkManagerToken;
use think\facade\Route;


// 不需要验证
Route::group('manager', function () {
    // 注册用户
    Route::post('register', 'manager.Manager/register');
    // 用户登录
    Route::post('login', 'manager.Manager/login');
})->allowCrossDomain();

// 管理员
Route::group('manager', function () {
    // 获取用户列表
    Route::get('getuserlist/:page/:limit', 'manager.Manager/getuserlist');
    // 删除用户 超级管理员拥有该权限
    Route::get('getSickUser/:page/:limit', 'manager.Manager/getSickUser');
    // 更新用户注册信息 更改sick 用户状态
    Route::post('updateuser', 'manager.Manager/updateUser');
    // 更新用户注册信息 更改sick 用户状态
    Route::post('addSick', 'manager.Manager/addSick');
    // 删除用户
    Route::post('delete', 'manager.Manager/delete');
    // 获取用户所有信息
    Route::post('getUserInfo', 'manager.Manager/getUserInfo');
    // 退出登录
    Route::post('logout', 'manager.Manager/logout');
})->middleware(checkManagerToken::class);
