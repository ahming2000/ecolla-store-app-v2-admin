@extends('layouts.app')

@section('title')
    订单 {{ $order->code }}
@endsection

@section('content')

    <main class="container">

        <div class="h1">订单 {{ $order->code }} 的物品</div>
        <table class="table table-bordered">
            <thead>
            <tr>
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
                    <td>{{ $orderItem->barcode }}</td>
                    <td>
                        RM{{ number_format($orderItem->price * $orderItem->discount_rate, 2, '.', '') }}
                    </td>
                    <td>{{ $orderItem->quantity }}</td>
                    <td>
                        RM{{ number_format($orderItem->price * $orderItem->discount_rate * $orderItem->quantity, 2, '.', '') }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>总计：</td>
                <td>RM{{ number_format($order->getSubtotal(), 2, '.', '') }}</td>
            </tr>
            </tbody>
        </table>
    </main>

@endsection
