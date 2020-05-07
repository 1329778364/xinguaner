<?php

declare(strict_types=1);

namespace app\model;

use app\controller\FileController;
use app\Request;
use think\Model;

/**
 * @mixin think\Model
 */
class Image extends Model
{
    //// 上传多图
    public function uploadMore()
    {
        $userId  = Request()->userInfo["id"];
        $image = $this->upload($userId, 'imglist', "userImg");
        $imageCount = count($image);
        for ($i = 0; $i < $imageCount; $i++) {
            $image[$i]['url'] = getFileUrl($image[$i]['url']);
        }
        return $image;
    }

    // 上传图片
    public function upload($userid = '', $field = '')
    {
        // 获取图片
        $files = request()->file($field);
        if (is_array($files)) {
            // 多图上传
            $arr = [];
            foreach ($files as $file) {
                $res = FileController::UploadEvent($file);
                if ($res['status']) {
                    $arr[] = [
                        'url' => $res['data'],
                        'user_id' => $userid
                    ];
                }
            }
            // halt($arr);
            return $this->saveAll($arr);
        }
        // 单图上传
        if (!$files) ApiException('请选择要上传的图片', 10000, 200);
        // 单文件上传
        $file = FileController::UploadEvent($files);
        // 上传失败
        if (!$file['status']) ApiException($file['data'], 10000, 200);
        // 上传成功，写入数据库
        return self::create([
            'url' => $file['data'],
            'user_id' => $userid
        ]);
    }
}
