<?php
/**
 * Created by 信磊.
 * Date: 2017-10-05
 * Time: 14:54
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = '微信服务器接口调用失败';
    public $errorCode = 999;
}