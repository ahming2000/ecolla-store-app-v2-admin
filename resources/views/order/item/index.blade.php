@extends('layouts.app')

@section('title')
    订单 {{ $order->code }}
@endsection

@section('content')

    <main class="container">

        <div class="h1">订单物品</div>
        <table class="table table-light">
            <thead>
            <tr>
                <th>商品</th>
                <th>小计</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->orderItems as $orderItem)
                <tr>
                    <td>
                        {{ $orderItem->name }}<br>
                        货号：{{ $orderItem->barcode }}<br>
                        价钱：RM{{ number_format($orderItem->price * $orderItem->discount_rate, 2, '.', '') }}<br>
                        数量：{{ $orderItem->quantity }}个
                    </td>
                    <td>
                        RM{{ number_format($orderItem->price * $orderItem->discount_rate * $orderItem->quantity, 2, '.', '') }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <td class="text-end">总计：</td>
                <td>RM{{ number_format($order->getSubtotal(), 2, '.', '') }}</td>
            </tr>
            </tbody>
        </table>
    </main>

@endsection
