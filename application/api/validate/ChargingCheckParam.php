<?php
/**
 * Created by 信磊.
 * Date: 2017-10-11
 * Time: 17:25
 */

namespace app\api\validate;


class ChargingCheckParam extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'num' => 'require|isPositiveInteger',
        'parkType' => ['regex' => '/^(start|stop)$/']
    ];

    protected $message = [
        'id' => '用户id必须是正整数',
        'num' => '车位号必须是正整数',
        'parkType' => 'parkType只能为start或stop'
    ];
}