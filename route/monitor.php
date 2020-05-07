<?php
// 手机用户相关的操作

use app\middleware\checkMonitorToken;
use think\facade\Route;


// 不需要验证
Route::group('monitor', function () {
    // 注册用户
    Route::post('register', 'monitor.Monitor/register');
    // 用户登录
    Route::post('login', 'monitor.Monitor/login');
})->allowCrossDomain();

// 需要登录才能进行的操作
Route::group('monitor', function () {
    // 增加摄像头节点
    Route::post('addCamera', 'monitor.Monitor/addCamera');
    // 更新摄像头节点信息
    Route::post('updateCamera', 'monitor.Monitor/updateCamera');
    // 更新摄像头节点信息
    Route::post('createCameraCloseOrder', 'monitor.Monitor/createCameraCloseOrder');
    // 退出登录
    Route::post('logout', 'monitor. Monitor/logout');
})->middleware(checkMonitorToken::class);
