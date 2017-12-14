<?php
/**
 * Created by 信磊.
 * Date: 2017-10-07
 * Time: 19:09
 */

namespace app\api\model;


use app\lib\exception\TokenException;
use think\Cache;
use think\Request;

class Login extends BaseModel
{
    protected $table = 'm_user';

    public static function updateUserInfo ()
    {
        $request = Request::instance();
        $data = $request->post();
        // 验证token
        if (!isset($data['token'])) {
            throw new TokenException([
                'msg' => 'Token不能存在或无效'
            ]);
        }
        // 用token到缓存换取openid
        $CacheValue = Cache::get($data['token']);
        $CacheValue = json_decode($CacheValue);
        if (!isset($CacheValue->openid)) {
            // token已过期或无效
            throw new TokenException();
        }
        // 清理不必要字段
        unset($data['token']);
        // 更新用户数据
        $result = self::where('openid', '=', $CacheValue->openid)
            ->update($data);

        return $result;
    }

    public static function getUserInfo ($token)
    {
        // 校验Token 校验成功返回openid
        $openid = self::checkToken($token);
        // 根据openid查找用户
        $user = self::get(['openid' => $openid]);
//        $user = self::get(['openid' => 'o5dTt0MCMooemqJhjE1SP4QKwsRa']);
        if (empty($user->nickname)) {
            return 'fail';
        }
        else {
            return 'success';
        }

    }
}