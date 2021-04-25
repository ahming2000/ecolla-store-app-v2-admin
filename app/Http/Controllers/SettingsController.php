<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('setting');
    }

    public function changingLog(){

        $currentVersion = 'v1.0.2 Beta';
        $updateDate = 'N/A';
        $color = 'orange'; // Beta: orange, Release: green

        $logs = [
            [
                'version' => 'v1.0.2 Beta',
                'date' => 'N/A',
                'desc' => [
                    '后台：',
                    '修复订单查看界面的订单时间不准确的问题',
                    '商品属性更改界面已完善' //WIP
                ]
            ],
            [
                'version' => 'v1.0.1 Beta',
                'date' => '2021/04/23',
                'desc' => [
                    '订单管理更新：可以查看订单详情并可以调整订单状态',
                    '用户：批发显示功能优化'
                ]
            ],
        ];

        return view('changingLog', compact('currentVersion', 'updateDate', 'color', 'logs'));
    }
}
