<?php
/**
 * Created by 信磊.
 * Date: 2017-10-11
 * Time: 17:31
 */

namespace app\api\model;


use think\Cache;
use think\Exception;

class Charging extends BaseModel
{
    public static function setUserParkingByStart ()
    {
        $request = request()->post();

        self::checkToken($request['token']);
        // 设置车位停车状态，以及停车用户 或者停止使用状态
        // 0 为正在使用状态, 1 为闲置状态
        // 返回设置状态
        if ( $request['parkType'] == 'start' ) {
            return self::parkingStart($request);
        } else {
            return self::parkingStop($request);
        }

    }

    // 停止使用
    public static function parkingStop ($request)
    {
        // 取消车位停车状态
        // 创建订单
        // 计算停车时间，以及费用
        $data = [
            'user_id' => null,
            'state' => 1
        ];
        $result = model('ParkingLot')
            ->save($data, [ 'num' => $request['num'] ]);
//        if ( !$result ) {
//            return $result;
//            throw new Exception('停车状态设置错误');
//        }
        // 开始时间
        $date_start = Cache::get('user_id_' . $request['id'] . '_' . $request['num']);
        // 结束时间
        $date_end = time();
        // 结果(小时)
        $date = floor(($date_end - $date_start) % 86400 / 3600);
        // 费用 1H/2元
        $cost = $date * 2;
        // 如果为异常停车，则不生成订单
        if ( $request['status'] != 'error' ) {
            // 生成订单
            $parking_id = model('ParkingLot')
                ->where('num', '=', $request['num'])
                ->value('id');
            // 准备订单信息
            $order_data = [
                'parking_id' => $parking_id,
                'user_id' => $request['id'],
                'order_number' => date('Ymd', time()) . time() . rand(111111, 999999),
                'time_start' => $date_start,
                'end_time' => $date_end
            ];
            $order_result = model('Order')->save($order_data);
            if ( !$order_result ) {
                throw new Exception('订单创建失败');
            }
        }
        Cache::rm('user_id_' . $request['id'] . '_' . $request['num']);
        $result = [ 'status' => 'stop', 'cost' => $cost ];
        return $result;
    }

    // 开始使用
    public static function parkingStart ($request)
    {

        Cache::rm('user_id_' . $request['id'] . '_' . $request['num']);
        $data = [
            'user_id' => $request['id'],
            'state' => 0
        ];
        // 停车时间
        Cache::set('user_id_' . $data['user_id'] . '_' . $request['num'], time());
        $result = model('ParkingLot')
            ->where('num', '=', $request['num'])
            ->update($data);
        if ( !$result ) {
            throw new Exception('暂无法使用车位');
        }
        return 'start';
    }

    // 计费状态
    public static function getChargingStatus ()
    {
        $token = request()->param('token');
        $openid = self::checkToken($token);
        $user_id = model('User')
            ->where('openid', '=', $openid)
            ->value('id');
        $result = model('ParkingLot')
            ->where('user_id', '=', $user_id)
            ->field('longitude,latitude,num,state')
            ->find();

        // 开始时间
        $date_start = Cache::get('user_id_' . $user_id . '_' . $result['num']);
        if ( $date_start ) {
            // 结束时间
            $date_end = time();
            // 结果(小时)
            $date_H = floor(($date_end - $date_start) % 86400 / 3600);
            // 获得分钟
            $result['date'] = floor(($date_end - $date_start) % 86400 / 60);
            // 费用 1H/2元
            $result['cost'] = $date_H * 2;
            return $result;
        } else {
            $result = [
                'cost' => 0,
                'date' => 0,
                'num' => $result['num']
            ];
            return $result;

        }

    }
}