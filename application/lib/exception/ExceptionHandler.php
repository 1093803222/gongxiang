<?php
/**
 * Created by 信磊.
 * Date: 2017-10-05
 * Time: 12:39
 */

namespace app\lib\exception;


use Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    // 需要返回客户端当前请求的url路径
    // 重写TP5内置的全局异常render()方法
    public function render (Exception $e)
    {
        if ($e instanceof BaseException) {
            // 如果为自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }
        else {
            // 如果为服务器异常
            if (config('app_debug')) {    // 自定义异常页面开关
                // 返回tp5内置异常页面
                return parent::render($e);
            }
            else {
                // 返回自定义异常页面
                $this->code = 500;
                $this->msg = '服务器内部错误，不想告诉你';
                $this->errorCode = 999;
                $this->recordErrorLog($e);  // 记录日志
            }
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url() // 获取当前请求的url
        ];
        return json($result, $this->code);
    }

    // 记录错误日志信息
    private function recordErrorLog (Exception $e)
    {
        // 初始化日志
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        // 记录日志
        Log::record($e->getMessage(), 'error');
    }
}