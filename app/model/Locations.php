<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Locations extends Model
{
    //  
    public function saveLocation($data, $id)
    {
        $this->create([
            'user_id' => $id,
            "latitude" => $data["latitude"],
            "longitude" => $data["longitude"]
        ]);
        return true;
    }
}
