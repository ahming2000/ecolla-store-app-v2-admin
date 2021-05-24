@extends('layouts.app')

@section('title')
    修改订单 {{ $order->code }}
@endsection

@section('content')

    <main class="container">

        @if(session()->has('message'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="h1">订单 {{ $order->code }} 查看</div>

        <div class="row">
            <div class="col-md-6 offset-md-3 col-sm-12 mb-3">
                <div class="h3">订单详情</div>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>订单编号：</td>
                        <td>{{ $order->code }}</td>
                    </tr>
                    <tr>
                        <td>订单模式：</td>
                        <td>{{ $order->getOrderModeLabel() }}</td>
                    </tr>
                    <tr>
                        <td>付款方式：</td>
                        <td>{{ $order->payment_method }}</td>
                    </tr>
                    <tr>
                        <td>状态：</td>
                        <td>{{ $order->getStatusDesc() }}</td>
                    </tr>
                    <tr>
                        @if($order->mode == 'pickup')
                            <td>电话号码：</td>
                        @else
                            <td>邮寄追踪ID：</td>
                        @endif
                        <td>{{ $order->delivery_id }}</td>
                    </tr>
                    <tr>
                        <td>邮寄费用：</td>
                        <td>RM{{ number_format($order->shipping_fee, 2, '.','') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

            @if(auth()->user()->hasAccess('order_update'))
        <div class="row">

            <div class="col-md-6 offset-md-3 col-sm-12 mb-3">
                <form action="{{ url('/order/' . $order->id) }}" method="post">

                    @csrf
                    <div class="h3">更改订单状态</div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="pending" value="pending">
                        <label class="form-check-label" for="pending">
                            准备中
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="prepared" value="prepared">
                        <label class="form-check-label" for="prepared">
                            待收货
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="refunded" value="refunded">
                        <label class="form-check-label" for="refunded">
                            已退款
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="canceled" value="canceled">
                        <label class="form-check-label" for="canceled">
                            已取消
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="completed" value="completed">
                        <label class="form-check-label" for="completed">
                            已完成
                        </label>
                    </div>

                    <div id="additional">
                        @if($order->mode == 'delivery' && $order->status != 'pending')
                            <label for="delivery_id">邮寄追踪ID</label>
                            <input type="text"
                                   class="form-control @error('delivery_id') is-invalid @enderror"
                                   name="delivery_id"
                                   value="{{ old('delivery_id') ?? $order->delivery_id ?? "" }}">

                            @error('delivery_id')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        @endif
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
@endif
        <div class="row">
            <div class="col-md-6 offset-md-3 col-sm-12 mb-3">
                <div class="h3">更多</div>
                <br>
                <div class="row d-flex justify-content-center">
                    @if(auth()->user()->hasAccess('order_item_view'))
                    <a href="{{ url('/order/' . $order->id . '/item') }}" class="btn btn-primary">显示订单物品</a>
                    @endif
                        @if(auth()->user()->hasAccess('order_receipt_view'))
                    <a href="{{ $order->receipt_image }}" class="btn btn-primary">查看订单收据</a>
                            @endif
                </div>
            </div>
        </div>
    </main>
@endsection

@section('extraScriptEnd')
    <script>

        function getDeliveryIdLayout() {
            return `<label for="delivery_id">邮寄追踪ID</label><input type="text" class="form-control" name="delivery_id" value="{{ $order->delivery_id ?? "" }}">`;
        }

        $(document).ready(function () {
            // Check the current status
            $('#{{ $order->status }}').attr('checked', 'checked');

            // Only display the delivery id key in column when it is delivery mode and other than status pending
            @if($order->mode == 'delivery')
            $('input[name=status]').on('change', function () {
                if ($(this).val() == 'pending') {
                    $('#additional').html('');
                } else {
                    $('#additional').html(getDeliveryIdLayout());
                }
            });
            @endif
        });
    </script>
@endsection
