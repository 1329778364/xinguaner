<?php

declare(strict_types=1);

namespace app\controller\manager;

use app\BaseController;
use app\controller\user\User as UserUser;
use app\model\Sick;
use app\model\User;
use think\Request;

class Manager extends BaseController
{

    protected $excludeValidateCheck = ['logout'];

    public function register(Request $request)
    {
        // halt("暗室逢灯");
        $param = $request->param();
        $this->M->save($param);
        return showSuccess();
    }

    // 用户登录
    public function login()
    {
        $user = cms_login(["data" => $this->request->ManagerModel, "tag" => "manager"]);
        return showSuccess($user);
    }
    // 管理员退出
    public function logout(Request $request)
    {
        return showSuccess(cms_logout([
            'token' => $request->header('token'),
            "tag" => "manager"
        ]));
    }

    public function getuserlist()
    {
        $param = $this->request->param();
        $user = User::page($param["page"], $param["limit"])->select()->hidden(["password"])->order("id");
        return showSuccess($user);
    }

    public function getSickUser(Request $request)
    {
        $param = $request->param();
        $user = User::page($param["page"], $param["limit"])->where(["sick" => 0])->hidden(["password"])->select();
        return showSuccess($user);
    }

    // 对特定ID 
    public function updateUser(Request $request)
    {
        $param = $request->param();
        $res = User::update(["id" => $param["user_id"], "sick" => $param["sick"]]);
        return showSuccess($res, "更新成功");
    }

    // 增加 患者 
    public function addSick(Request $request)
    {
        $param = $request->param();
        $res = Sick::create($param);
        return showSuccess($res["id"]);
    }

    // public function delete()
    // {
    //     $res = $this->request->model->locations()->userinfo()->delete();
    //     return showSuccess($res);
    // }

    public function getUserInfo()
    {
        $user = User::where("id", $this->request->param("user_id"))->hidden(["password"])->with([
            "userinfo" => function ($query) {
                return $query->hidden(["id"]);
            },
            'locations' => function ($query) {
                return $query->hidden(["id", "user_id"])->order("create_time");
            },
            'bluetooth' => function ($query) {
                return $query->hidden(["id", "user_id"])->order("create_time");
            }
        ])->select();
        return showSuccess($user);
    }
}
