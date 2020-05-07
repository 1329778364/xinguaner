<?php

declare(strict_types=1);

namespace app\validate\manager;

use app\validate\BaseValidate;

class Manager extends BaseValidate
{
    // TODO 完善验证规则 
    protected $rule = [
        "user_id|用户user_id" => "require|integer|>:0|isExist:user", // 模型名称
        "id|管理者id" => "require|integer|>:0|isExist:manager", // 模型名称
        "managername|机构名称" => "require|min:2|max:20",
        "username|负责人" => "require|min:2|max:20", //unique:user
        "password|密码" => "require|min:6|max:20",
        "phone|手机号" => "require",
        "address|地址" => "require",
        "latitude" => "require",
        "longitude" => "require",
        "page" => "require|integer|>:0",
        "limit" => "require|integer|>:0",
        "sick" => "require|integer|in:0,1,2"
    ];

    protected $scene = [
        "delete" => ["user_id"],
        "getuserlist" => ["page"],
        "getSickUser" => ["page", "limit"],
        "updateUser" => ["user_id", "sick"],
        "getUserInfo" => ["user_id"],
    ];

    public function sceneRegister()
    {
        return $this->only(["managername", "password", "username", "phone", "address", "latitude", "longitude"])->append("managername", "unique:manager");
    }

    public function sceneLogin()
    {
        return $this->only(["managername", "password"])->append("password", "checkmanagerlogin");
    }

    public function sceneAddSick()
    {
        return $this->only(["user_id", "manager_id"])->append("user_id", "unique:sick");
    }
}
