<?php
/**
 * Created by 信磊.
 * Date: 2017-10-05
 * Time: 13:14
 */

namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct ($code)
    {
        // 拼写完整的url路径
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        // 填上login_url占位符
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    // 获取Token
    public function get ()
    {
        // 请求微信服务器
        $result = curl_get($this->wxLoginUrl);
        // 将得到的json转为数组
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key及openID时发生异常，微信内部错误');
        }
        else {
            // 检验参数中是否存在错误码
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            }
            else {
                // 没有错误，则颁发Token令牌
                return $this->grantToken($wxResult);
            }
        }

    }

    // 生成令牌
    private function grantToken ($wxResult)
    {
        // 拿到openid
        // 数据库里查看一下，这个openid是否存在
        // 如果存在，则不处理，如果不存在，则新增一条记录
        // 生成令牌，准备缓存数据，写入缓存
        // 把令牌返回到客户端
        // 存入缓存：
        // key：令牌
        // value：wxResult，uid（用户主键）
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user) {
            $uid = $user->id;
        }
        else {
            // openid不存在数据库，则新增一条记录
            $uid = $this->newUser($openid);
        }
        // 准备缓存数据
        $cachedValue = $this->preparCacheValue($wxResult, $uid);
        // 写入缓存
        $token = $this->saveToCache($cachedValue);
        return $token;

    }

    // 写入缓存
    private function saveToCache ($cachedValue)
    {
        // 生成token
        $key = self::generateToken();
        // 转换成字符串再存入
        $value = json_encode($cachedValue);
        // 设置token过期时间
        $expire_in = config('setting.token_expire_in');

        // 写入缓存
        $request = cache($key, $value, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    // 准备缓存数据
    private function preparCacheValue ($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        return $cachedValue;
    }
    // openid不存在数据库，则新增一条记录
    private function newUser ($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    // 微信服务器返回值异常处理函数
    private function processLoginError ($wxResult)
    {
        # 此处也可记录日志
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}