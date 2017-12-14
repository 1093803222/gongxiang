<?php
/**
 * Created by 信磊.
 * Date: 2017-10-08
 * Time: 17:22
 */

namespace app\api\controller\v1;

use app\api\model\Order as OrderModel;

class Order
{
    public function getOrderHistory ()
    {
        $token = request()->param('token');
        $order = OrderModel::orderHistory($token);
        return $order;
    }
}