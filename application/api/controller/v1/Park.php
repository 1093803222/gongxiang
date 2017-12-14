<?php
/**
 * Created by 信磊.
 * Date: 2017-10-09
 * Time: 11:51
 */

namespace app\api\controller\v1;

use app\api\model\ParkingLot as ParkingLotModel;

class Park
{
    public function getParkingList ()
    {
        $token = request()->param('token');
        // 校验token是否有效或者已过期
        $park_list = ParkingLotModel::getEmptyParking($token);
        return $park_list;
    }

    public function getParking ()
    {
        $num = input('param.num/d');
        $park = ParkingLotModel::get($num);
        if (!empty($park['user_id']) && $park['state'] == 1) {
            ParkingLotModel::where('num', '=', $num)->update(['user_id' => null]);
            return false;
        }
        return true;
    }
}