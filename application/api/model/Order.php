<?php
/**
 * Created by 信磊.
 * Date: 2017-10-08
 * Time: 16:35
 */

namespace app\api\model;


class Order extends BaseModel
{
    // 关联User模型
    public function user ()
    {
        return $this->belongsTo('User', 'user_id', 'id')
            ->field('id,number');
    }
    // 关联车位表
    public function parkingLot ()
    {
        return $this->belongsTo('ParkingLot', 'parking_id', 'id')
            ->field('id,address');
    }
    // 历史停车次数
    public static function historyOrderNum ($openid)
    {
        $parkingNum = self::hasWhere('User', ['openid' => $openid])->count();
        return $parkingNum;
    }

    // 历史订单
    public static function orderHistory ($token)
    {
        $openid = self::checkToken($token);
        $order = self::hasWhere('user', ['openid' => $openid])
            ->with(['user', 'parkingLot'])
            ->where('end_time', 'not null')
            ->order('end_time desc')
            ->select();
        foreach ($order as $key => &$val) {
            // 计算时长 时间戳：  （结束时间 - 开始时间）% 86400秒 / 3600分钟 = 总小时
            $val['time_count'] = floor(($val['end_time'] - $val['time_start']) % 86400 / 3600);
            // 费用计算  2H/元
            $val['cost'] = $val['time_count'] * 2;
            $val['time_start'] = date('Y-m-d H:i', $val['time_start']);
            // 清除无用字段
            unset($val['end_time']);
            unset($val['user_id']);
            unset($val['parking_id']);
            unset($val['user']['id']);
            unset($val['parking_lot']['id']);
        }
        return $order;
    }
}