<?php
/**
 * Created by 信磊.
 * Date: 2017-10-07
 * Time: 16:26
 */

namespace app\api\controller\v1;


use app\api\model\Login as LoginModel;
use app\api\validate\LoginCheck;
use app\lib\exception\ParameterException;

class Login
{
    public function userInfo ()
    {
        (new LoginCheck())->goCheck();
        $result = LoginModel::updateUserInfo();
        if ($result !== 0 && $result !== 1) {
            throw new ParameterException(['msg' => '更新资料失败']);
        }
        return json(["status" => "success"]);
    }

    public function checkLogin (){
        $token = request()->post('token');
        return LoginModel::getUserInfo($token);
    }
}