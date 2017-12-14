<?php
/**
 * Created by 信磊.
 * Date: 2017-10-05
 * Time: 12:12
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;
use think\Cache;
use app\lib\exception\TokenException;

class BaseValidate extends Validate
{
    // 请求参数校验
    public function goCheck ()
    {
        // 取得所有http请求参数
        $request = Request::instance();
        $params = $request->param();
        // 进行批量校验
        $result = $this->batch()->check($params);
        if (!$result) {
            $e = new ParameterException([
                'msg' => $this->error,
            ]);
            // 抛出异常
            throw $e;
        }
        else {
            return true;
        }
    }

    // 空值校验
    protected function isNotEmpty ($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /*
     * 验证正整数
     * @value 被验证的值
     * @rule  验证规则
     * @data  所有数据
     * @field 验证字段
     * */
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        //验证id是否为大于0的正整数
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        else {
            return false;
        }
    }
}