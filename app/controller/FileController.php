<?php

declare(strict_types=1);

namespace app\controller;

use think\Request;

class FileController
{
    // 上传单文件
    static public function UploadEvent($files, $path = "userImg")
    {
        $size = 1024 * 2048;
        validate([
            "img" => 'fileSize:' . $size . '|fileExt:jpg,png,gif,jpeg'
        ])->check(["files" => $files]);
        // validate(['size' => $size, 'ext' => $ext])->check(["files" => $files]);
        $savename = \think\facade\Filesystem::putFile($path, $files);
        return [
            'data' => str_replace('\\', '/', $savename),
            'status' => $savename ? true : false
        ];
    }
}
