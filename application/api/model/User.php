<?php
/**
 * Created by 信磊.
 * Date: 2017-10-05
 * Time: 15:08
 */

namespace app\api\model;


class User extends BaseModel
{
    protected $hidden = ['openid'];

    public static function getByOpenID ($openid)
    {
        $user = self::where('openid', '=', $openid)
            ->find();
        return $user;
    }

    public static function getUserInfoByOpenID ($token)
    {
        // 校验token 成功返回openid
        $openid = self::checkToken($token);
        $user_info = self::get(['openid' => $openid]);
        $user_info['parking_num'] = Order::historyOrderNum($openid);
        return $user_info;
    }
}