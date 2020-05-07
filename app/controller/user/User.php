<?php

declare(strict_types=1);

namespace app\controller\user;

use app\BaseController;
use app\model\Locations;
use app\model\Role;
use think\Request;

class User extends BaseController
// 主要面向APP端进行服务 
{

    // 自动获取模型名称
    // protected $autoModel = true;
    //  自定义模型路径
    // protected $modelPath = null;
    // 自定义场景
    // protected $autoValidateScene = [
    //     "save" => "sace1"
    // ];
    // protected $exludeVlidateCheck = ["index"];
    // 不需要验证
    protected $excludeValidateCheck = ['logout', "getUserDetail"];
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function register(Request $request)
    {
        $param = $request->param();
        $this->M->save($param);
        return showSuccess();
    }


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $param = $this->request->param();
        $limit = getValByKey("limit", $param, 10);
        $keyword = getValByKey("keyword", $param, "");

        $where = [
            ["username", "like", "%" . $keyword . "%"]
        ];

        $totalCount = $this->M->where($where)->count();
        $list = $this->M
            ->page($param["page"], $limit)
            ->where($where)
            ->with(["role"])
            ->order("id", "desc")->select()->hidden(["password"]);
        $roles = Role::field(["id", "name"])->select();
        return showSuccess([
            "list" => $list,
            "totalCount" => $totalCount,
            "roles" => $roles
        ]);
    }

    public function postlocation(Request $request)
    {
        // 获取请求的数据
        $param = $request->param();
        $userinfo = $this->request->userInfo;
        (new Locations)->saveLocation($param, $userinfo["id"]);
        return showSuccess();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $param = $request->param();
        $id = $request->userInfo["id"];
        $res = $request->UserModel->save([
            "id" => $id,
            "username" => $param["username"],
            "password" => $param["password"],
            "phone" => $param["phone"],
            "avatar" => $param["avatar"],
            "userpic" => $param["userpic"]
        ]);
        return showSuccess($res, "更新成功");
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $user = $this->request->model;

        //  不能删除自己
        if ($this->request->UserModel->id === $user->id) {
            ApiException("无法删除自己");
        }
        //  不能删除其他超级管理者账号
        if ($user->role_id === 1) {
            ApiException("不能删除其他超级管理者账号");
        }
        return showSuccess($user->delete(), "删除成功");
    }

    // 用户登录
    public function login()
    {
        $user = cms_login(["data" => $this->request->UserModel, "tag" => "user"]);
        return showSuccess($user);
    }

    // 管理员退出
    public function logout(Request $request)
    {
        return showSuccess(cms_logout([
            'token' => $request->header('token'),
            "tag" => "user"
        ]));
    }

    public function getUserDetail()
    {
        $id = $this->request->UserModel["id"];
        $res = $this->M->where('id', $id)->hidden(["password"])->with([
            "userinfo",
            "locations"
        ])->select();
        return showSuccess($res);
    }
}
