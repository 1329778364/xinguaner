<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class User extends Model
{
    // 关联角色表，多对一的关系 反向一对多
    public function role()
    {
        return $this->belongsTo("role");
    }

    public function locations()
    {
        return $this->hasMany("locations");
    }

    public function userinfo()
    {
        return $this->hasOne("userinfo");
    }

    public function bluetooth()
    {
        return $this->hasMany("bluetooth");
    }

    // 修改器当有数据保存时自动修改对应字段的数据
    public function setPasswordAttr($value, $data)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }
}
