<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Role extends Model
{
    // 一对多关联用户
    public function users()
    {
        return $this->hasMany("User");
    }
}
