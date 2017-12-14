<?php
/**
 * Created by 信磊.
 * Date: 2017-10-05
 * Time: 13:14
 */

namespace app\api\service;


/** Token基类 **/
class Token
{
    // 生成Token  （三组字符串进行MD5加密的生成的Token令牌）
    public static function generateToken ()
    {
        // 生成32个字符组成的随机字符串
        $randChars = getRandChar(32);
        // 生成时间
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // 生成盐密钥
        $salt = config('secure.token_salt');
        return md5($randChars . $timestamp . $salt);
    }
}