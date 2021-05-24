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
                        @if(auth()->user()->hasAccess('order_receipt_view'))
                        <button class="btn btn-primary btn-sm" onclick="viewReceipt(this)" value="{{ url($order->receipt_image) }}">查看订单收据</button>
                            @endif
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
                    <td class="p-0 h-100">
                        <div class="d-flex p-2 align-items-center justify-content-center h-100">
                            <div class="d-flex flex-sm-column align-items-center">
                                @if(auth()->user()->hasAccess('order_item_view'))
                                <a class="btn btn-primary btn-sm" href="{{ url('/order/' . $order->id . '/item') }}">显示订单物品</a>
                                    @endif
                            </div>
                        </div>
                    </td>
                    <td class="p-0 h-100">
                        <div class="d-flex p-2 align-items-center justify-content-center h-100">
                            <div class="d-flex flex-sm-column align-items-center">

                                <a class="btn btn-primary btn-sm m-2" href="{{ url('/order/' . $order->id) }}">更改订单状态</a>

                                    @if(auth()->user()->hasAccess('order_invoice_download'))
                                    <a class="btn btn-primary btn-sm m-2" href="{{ URL::to('/order/' . $order->id . '/pdf') }}">下载订单收据</a>
                                        @endif
                            </div>
                        </div>

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
