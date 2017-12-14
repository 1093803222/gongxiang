<?php
/**
 * Created by 信磊.
 * Date: 2017-10-08
 * Time: 13:43
 */

namespace app\api\controller\v1;

use app\api\model\User as UserModel;

class User
{
    public function getUserInfo ()
    {
        $token = request()->post('token');
        $user_info = UserModel::getUserInfoByOpenID($token);
        return $user_info;
    }
}