<?php
/**
 * Created by 信磊.
 * Date: 2017-10-08
 * Time: 17:30
 */

namespace app\api\model;


class ParkingLot extends BaseModel
{
    public static function getEmptyParking ($token)
    {
        self::checkToken($token);
        $park_list = self::where('state', '=', 1)->select();
        
        return $park_list;
    }
}