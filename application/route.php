<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

// 获取token令牌
Route::post('api/:version/token/user', 'api/:version.Token/getToken');

// 第一次登陆更新用户信息
Route::post('api/:version/login', 'api/:version.Login/userInfo');
// 检测是否首次登陆
Route::post('api/:version/login/check', 'api/:version.Login/checkLogin');

// 用户信息
Route::post('api/:version/user', 'api/:version.User/getUserInfo');

// 历史订单
Route::get('api/:version/history/:token', 'api/:version.Order/getOrderHistory');

// 根据车位号取得一个车位信息
Route::get('api/:version/park/:num', 'api/:version.Park/getParking', [], ['num' => '\d+$']);
// 空车位列表
Route::get('api/:version/park/:token', 'api/:version.Park/getParkingList');

// 计费
Route::post('api/:version/charging', 'api/:version.Charging/setChargingStart');
Route::get('api/:version/charging/:token', 'api/:version.Charging/checkChargingStatus');