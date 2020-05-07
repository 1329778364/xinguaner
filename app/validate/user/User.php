<?php

declare(strict_types=1);

namespace app\validate\user;

use app\validate\BaseValidate;

class User extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    // TODO 完善验证规则 
    protected $rule = [
        "username|用户名" => "require|min:5|max:20", //unique:user
        "id|用户id" => "require|integer|>:0|isExist:User", // 模型名称
        "password|密码" => "require|min:5|max:20",
        "phone|手机号" => "require",
        "avater|头像" => "url",
        "role_id|角色" => "require|integer|>:0",
        "status|状态" => "require|integer|in:0,1,2",
        "page" => "require|integer|>:0",
        "latitude" => "require",
        "longitude" => "require"
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];


    protected $scene = [
        // "save" => ["username", "password", "avater", "phone"],
        // "update" => ["id", "username", "password", "avater", "phone"]
        "delete" => ["id"],
        "index" => ["page"],
        "postlocation" => ["latitude", "longitude"]
    ];

    public function sceneRegister()
    {
        return $this->only(["username", "password", "avater", "phone"])->append("username", "unique:user");
    }

    public function sceneUpdate()
    {
        // $id = request()->param("id");
        // 重复性检验时 排除 自身id的数据
        return $this->only(["username", "password", "avater", "phone"])->append("username", "unique:user");
    }

    public function sceneLogin()
    {
        return $this->only(["username", "password"])->append("password", "checklogin");
    }
}
