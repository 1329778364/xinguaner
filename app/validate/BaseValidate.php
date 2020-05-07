<?php

declare(strict_types=1);

namespace app\validate;

use app\model\Manager as ModelManager;
use app\model\Monitor;
use app\model\User;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];


    // 自定义验证场景
    protected function isExist($value, $rule = "", $data = "", $field = "", $title = "")
    {
        if (!$value) {
            return;
        }
        $model = "\app\model\\" . $rule;
        $m = $model::find($value);
        if (!$m) {
            return "该" . $title . "不存在";
        }
        request()->model = $m;
        return true;
    }

    // 验证用户 密码是否正确 或存在
    protected function checklogin($value, $rule = "", $data = "", $field = "", $title = "")
    {
        $M = User::where('username', $data["username"])->find();
        if (!$M) {
            return "用户名错误";
        }
        if (!password_verify($data["password"], $M->password)) {
            return "密码错误";
        }
        // 挂载用户信息
        request()->UserModel = $M;
        return true;
    }

    // 验证用户 密码是否正确 或存在
    protected function checkmanagerlogin($value, $rule = "", $data = "", $field = "", $title = "")
    {
        $M = ModelManager::where('managername', $data["managername"])->find();
        if (!$M) {
            return "用户名错误";
        }
        if (!password_verify($data["password"], $M->password)) {
            return "密码错误";
        }
        // 挂载用户信息
        request()->ManagerModel = $M;
        return true;
    }

    // 验证用户 密码是否正确 或存在
    protected function checkmonitorlogin($value, $rule = "", $data = "", $field = "", $title = "")
    {
        $M = Monitor::where('monitorname', $data["monitorname"])->find();
        if (!$M) {
            return "用户名错误";
        }
        if (!password_verify($data["password"], $M->password)) {
            return "密码错误";
        }
        // 挂载用户信息
        request()->MonitorModel = $M;
        return true;
    }
}
