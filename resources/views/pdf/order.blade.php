<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        /*
        @font-face {
            font-family: yahei;
            src: url("
        {{public_path('fonts/')}}
        MicrosoftYaHeiLight-01.ttf") format('truetype');
                    font-weight: lighter;
                }

                @font-face {
                    font-family: yahei;
                    src: url("
        {{public_path('fonts/')}}
        MicrosoftYaHei-01.ttf") format('truetype');
                    font-weight: normal;
                }

                @font-face {
                    font-family: yahei;
                    src: url("
        {{public_path('fonts/')}}
        MicrosoftYaHei-Bold-01.ttf") format('truetype');
                    font-weight: bold;
                }
                */

        body {
            font-family: yahei;
        }

        .column {
            float: left;
            width: 50%;
            padding: 10px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        table {
            width: 100%;
        }

        td {
            padding: 10px;
        }

        .text-align-center {
            text-align: center;
        }

        .text-align-right {
            text-align: right;
        }
    </style>
</head>

<body>
<div class="container">
    <img src="{{ asset('img/icon/ecolla_icon.png') }}" width="75px" height="75px"/>
    <h2>订单 {{ $order->code }}</h2>
    <div class="row">
        <div class="column">
            @if($order->mode == 'delivery')
                姓名：{{ $order->customer->name }}<br>
                电话号码：{{ $order->customer->phone }}<br>
                运送地址：{{ $order->customer->addressLine1 }}
            @else
                电话号码：{{ $order->delivery_id }}
            @endif
        </div>
        <div class="column">
            开单日期和时间：{{ $order->getOrderCreateDateTime() }}
            订单模式：{{ $order->getOrderModeLabel() }}
        </div>
    </div>

    <div class="row">
        <table>
            <thead>
            <tr class="normal">
                <th>名称</th>
                <th>货号</th>
                <th>价钱</th>
                <th>数量</th>
                <th>小计</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->orderItems as $orderItem)
                <tr>
                    <td>{{ $orderItem->name }}</td>
                    <td class="text-align-center">{{ $orderItem->barcode }}</td>
                    <td class="text-align-right">
                        RM{{ number_format($orderItem->price * $orderItem->discount_rate, 2, '.', '') }}
                    </td>
                    <td class="text-align-right">{{ $orderItem->quantity }}</td>
                    <td class="text-align-right">
                        RM{{ number_format($orderItem->price * $orderItem->discount_rate * $orderItem->quantity, 2, '.', '') }}
                    </td>
                </tr>
            @endforeach



            @if($order->mode == 'delivery')

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-align-center">商品总计：</td>
                    <td class="text-align-right">RM{{ number_format($order->getSubtotal(), 2, '.', '') }}</td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-align-center">邮费总计：<br>@if($order->free_shipping_note != null)（{{ $order->free_shipping_note }}）@endif</td>
                    <td class="text-align-right">RM{{ number_format($order->shipping_fee, 2, '.', '') }}</td>
                </tr>
            @endif

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-align-center">全部总计：</td>
                <td class="text-align-right">RM{{ number_format($order->getSubtotal() + $order->shipping_fee, 2, '.', '') }}</td>
            </tr>

            </tbody>
        </table>
    </div>

</div>
</body>

</html>
