<?php
/**
 * Created by 信磊.
 * Date: 2017-10-05
 * Time: 15:09
 */

namespace app\api\model;

use think\Model;
use think\Cache;
use app\lib\exception\TokenException;

class BaseModel extends Model
{
    public static function checkToken ($token = '')
    {
        // 校验token 不存在或者已过期
        if (empty($token)) throw new TokenException();
        $CacheValue = json_decode(Cache::get($token));
        if (!isset($CacheValue->openid)) throw new TokenException();
        return $CacheValue->openid;
    }
}