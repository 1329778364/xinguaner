<?php
// 应用公共文件

// 抛出异常

use think\facade\Cache;

function ApiException($msg = '请求错误', $errorCode = 20000, $statusCode = 404)
{
    abort($errorCode, $msg, [
        'statusCode' => $statusCode
    ]);
}

// 成功
function showSuccess($data = "", $msg = "ok", $code = 200)
{
    return json([
        "msg" => $msg,
        "data" => $data
    ], $code);
}

//  失败
function showError($msg = "fail", $code = 400)
{
    return json([
        "msg" => $msg,
    ], $code);
}


// 获取数组指定key的value
function getValByKey($key, $arr, $default = false)
{
    return array_key_exists($key, $arr) ? $arr[$key] : $default;
}


/**
 * 登录（设置并存储token）
 *
 * @param array $param  参数配置(data,password,tag,expire)
 * @return void
 */
function cms_login(array $param)
{
    // 获取参数
    $data = getValByKey('data', $param);
    if (!$data) return false;
    // 标签分组
    $tag = getValByKey('tag', $param, 'user');
    // 是否返回密码
    $password = getValByKey('password', $param);
    // 登录有效时间 0为永久
    $expire = getValByKey('expire', $param, 0);

    $CacheClass = \think\facade\Cache::store(config('cms.' . $tag . '.token.store'));
    // 生成唯一token
    $token = sha1(md5(uniqid(md5(microtime(true)), true)));
    // 拿到当前用户数据
    $user = is_object($data) ?  $data->toArray() : $data;
    // // 获取之前并删除token（防止重复登录）
    // $token = getValByKey('token', $param);
    // $beforeToken = $CacheClass->get($tag . '_' . $user['id']);
    // // 删除之前token对应的用户信息
    // if ($beforeToken) {
    //     cms_logout([
    //         'token' => $beforeToken,
    //         'tag' => $tag,
    //     ]);
    // }
    // 存储token - 用户数据
    $CacheClass->set($tag . '_' . $token, $user, $expire);
    // 存储用户id - token
    $CacheClass->set($tag . '_' . $user['id'], $token, $expire);
    // 隐藏密码
    if (!$password) unset($user['password']);
    // 返回token
    $user['token'] = $token;
    return $user;
}

/**
 * 退出登录（删除token）
 *
 * @param array $param 参数配置 (tag,token,password)
 * @return void
 */
function cms_logout(array $param)
{
    $tag = getValByKey('tag', $param, 'user');
    $token = getValByKey('token', $param);

    $user = Cache::store(config('cms.' . $tag . '.token.store'))->pull($tag . '_' . $token);

    if (!empty($user)) Cache::store(config('cms.' . $tag . '.token.store'))->pull($tag . '_' . $user['id']);

    unset($user["password"]);
    return $user;
}


/**
 * 获取用户信息（token校验）
 *
 * @param array $param 参数配置(tag,token,password)
 * @return void
 */
function cms_getUser(array $param)
{
    $tag = getValByKey('tag', $param, 'user');
    $token = getValByKey('token', $param);
    $password = getValByKey('password', $param);
    $user = \think\facade\Cache::store(config('cms.' . $tag . '.token.store'))->get($tag . '_' . $token);
    if (!$password) unset($user['password']);
    return $user;
}


// 获取文件完整url
function getFileUrl($url = '')
{
    if (!$url) return;
    return url($url, [], false, true);
}
