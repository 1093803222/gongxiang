<?php
/**
 * Created by 信磊.
 * Date: 2017-10-11
 * Time: 17:22
 */

namespace app\api\controller\v1;


use app\api\validate\ChargingCheckParam;
use app\api\model\Charging as ChargingModel;
use app\api\model\ParkingLot as ParkingLotModel;

class Charging
{
    public function setChargingStart ()
    {
        (new ChargingCheckParam())->goCheck();
        // 校验该车位是否已有人停车
        $park_status = ParkingLotModel::get(input('param.num/d'));
        if ($park_status['state'] == 0 && input('param.parkType') == 'start' ) return false;
        return ChargingModel::setUserParkingByStart();
    }

    /**
     * 根据token判断用户是否有正在停车的车位
     */
    public function checkChargingStatus ()
    {
        return ChargingModel::getChargingStatus();
    }
}