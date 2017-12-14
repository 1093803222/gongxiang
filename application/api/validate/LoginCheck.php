<?php
/**
 * Created by 信磊.
 * Date: 2017-10-07
 * Time: 18:52
 */

namespace app\api\validate;


class LoginCheck extends BaseValidate
{
    protected $rule = [
        'top'      => 'require',
        'nickname' => 'require',
        'phone'    => 'require',
        'number'   => 'require',
        'token'    => 'require'
    ];
}