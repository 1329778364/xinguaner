<?php

declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\Image as ModelImage;
use think\Request;

class Image extends BaseController
{
    public function uploadMore()
    {
        $list = $this->M->uploadMore();
        return showSuccess($list);
    }
}
