<?php
/**
 * Created by 信磊.
 * Date: 2017-10-05
 * Time: 12:27
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    // http状态码
    public $code = 400;
    // 错误信息
    public $msg = '参数错误';
    // 自定义错误码
    public $errorCode = 10001;


    public function __construct ($params = [])
    {
        // 如果参数不为数组，则忽略
        if (!is_array($params)) {
            return ;
        }
        // 如果参数为数组，则给予默认参数进行赋值
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }
    }
}