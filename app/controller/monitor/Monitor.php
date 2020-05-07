<?php

declare(strict_types=1);

namespace app\controller\monitor;

use app\BaseController;
use app\model\Camera;
use app\model\CameraCloseOrder;
use app\model\CloseContact;
use app\model\Role;
use think\Request;

class Monitor extends BaseController
// 主要面向APP端进行服务 
{
    // 不需要验证
    protected $excludeValidateCheck = ['logout'];
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
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $param = $request->param();
        $res = $request->model->save($param);
        return showSuccess($res, "更新成功");
    }

    // 用户登录
    public function login()
    {
        $user = cms_login(["data" => $this->request->MonitorModel, "tag" => "monitor"]);
        return showSuccess($user);
    }

    // 管理员退出
    public function logout(Request $request)
    {
        return showSuccess(cms_logout([
            'token' => $request->header('token')
        ]));
    }

    public function addCamera()
    {

        $model = $this->request->MonitorModel;
        $monitor_id = $model["id"];
        $param = request()->param();
        $param["monitor_id"] = $monitor_id;
        $param["camera_code"] = $monitor_id . $param["camera_code"];
        // 判断节点是否已经存在
        $data = Camera::where(['monitor_id' => $monitor_id, "camera_code" => $param["camera_code"]])->find();
        if (!$data) {
            $res = Camera::create($param);
            return showSuccess($res);
        } else {
            return ApiException("该节点已经存在");
        };
    }

    // TODO 提交 cameraClose d订单还没有完成
    public function createCameraCloseOrder()
    {
        $param = request()->param();
        $model = $this->request->MonitorModel;
        $monitor_id = $model["id"];
        $param["monitor_id"] = $monitor_id;
        $res = CameraCloseOrder::create($param);
        $data["camera_close_order_id"] = $res["id"];
        $data["user_id"] = $param["user_id"];
        CloseContact::create($data);
    }
}
