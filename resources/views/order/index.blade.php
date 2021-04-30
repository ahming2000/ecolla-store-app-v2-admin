@extends('layouts.app')

@section('title')
    订单查看
@endsection

@section('content')
    <main class="container">

        <div class="h1">订单查看</div>

        <div class="row">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">订单详情</th>
                    <th scope="col">顾客资料</th>
                    <th scope="col">订单物品</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>
                            {{ $order->code }}<br>
                            {{ $order->getOrderCreateDateTime() }}<br>
                            订单状态：{{ $order->getStatusDesc() }}<br>
                            <button class="btn btn-primary btn-sm" onclick="viewReceipt(this)" value="{{ url($order->receipt_image) }}">查看订单收据</button>
                        </td>
                        <td>
                            @if($order->mode == 'delivery')
                                姓名：{{ $order->customer->name }}<br>
                                电话号码：{{ $order->customer->phone }}<br>
                                运送地址：{{ $order->customer->addressLine1 }}
                            @else
                                电话号码：{{ $order->delivery_id }}
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ url('/order/' . $order->id . '/item') }}">显示订单物品</a>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ url('/order/' . $order->id) }}">更改订单状态</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>

    </main>

@endsection

@section('extraScriptEnd')
    <script>
        function viewReceipt(source) {
            let url = source.value;
            window.open(url, 'Image', 'width=400px,height=400px,resizable=1');
        }
    </script>
@endsection
