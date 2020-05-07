<?php
// 手机用户相关的操作

use app\middleware\checkUserToken;
use think\facade\Route;


// 不需要验证
Route::group('user', function () {
    // 注册用户
    Route::post('register', 'user.User/register');
    // 用户登录
    Route::post('login', 'user.User/login');
})->allowCrossDomain();

// 普通用户 需要登录才能进行的操作
Route::group('user', function () {
    // 更新用户注册信息
    Route::post('update', 'user.User/update');
    // 退出登录
    Route::post('logout', 'user.User/logout');
    // 获取用户信息
    Route::post('getUserDetail', 'user.User/getUserDetail');
    //  发送定位信息
    Route::post('postlocation', 'user.User/postlocation');
    // 上传多图
    Route::post('uploadMore', 'Image/uploadMore');
})->middleware(checkUserToken::class);
