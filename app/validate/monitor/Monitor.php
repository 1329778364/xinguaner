<?php

declare(strict_types=1);

namespace app\validate\monitor;

use app\validate\BaseValidate;

class Monitor extends BaseValidate
{
    // TODO 完善验证规则 
    protected $rule = [
        "id|摄像机构节点id" => "require|integer|>:0|isExist:monitor", // 模型名称
        "camera_id|摄像头ID" => "require|integer|>:0|isExist:camera",
        "sick_id|患者ID" => "require|integer|>:0|isExist:user",
        "camera_code|摄像头本地编号" => "require",
        "monitorname|摄像机构名称" => "require|min:2|max:20",
        "username|负责人" => "require|min:2|max:20", //unique:user
        "password|密码" => "require|min:6|max:20",
        "phone|手机号" => "require",
        "address|地址" => "require",
        "latitude" => "require",
        "longitude" => "require",
        "page" => "require|integer|>:0"
    ];

    protected $scene = [
        // "delete" => ["id"],
        // "index" => ["page"],
        "addCamera" => ["camera_code", "address", "longitude", "latitude", "status"],
        "createCameraCloseOrder" => ["sick_id", "camera_id"],
        "updateCamera" => []
    ];

    public function sceneRegister()
    {
        return $this->only(["monitorname", "password", "username", "phone", "address", "latitude", "longitude"])->append("monitorname", "unique:monitor");
    }

    public function sceneLogin()
    {
        return $this->only(["monitorname", "password"])->append("password", "checkmonitorlogin");
    }
}
