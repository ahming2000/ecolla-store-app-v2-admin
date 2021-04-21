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

        <div class="h1">修改订单属性</div>
        <form action="{{ url('/order/' . $order->id) }}" method="post">

            @csrf

            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12 mb-3">
                    <div class="h3">订单状态</div>
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
                        <input class="form-check-input" type="radio" name="status" id="canceled" value="canceled">
                        <label class="form-check-label" for="canceled">
                            已退款
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="refunded" value="refunded">
                        <label class="form-check-label" for="refunded">
                            已取消
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="completed" value="completed">
                        <label class="form-check-label" for="completed">
                            已完成
                        </label>
                    </div>


                </div>

                <div class="col-md-6 offset-md-3 col-sm-12 mb-3" id="additional">
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

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>
        </form>
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
                } else{
                    $('#additional').html(getDeliveryIdLayout());
                }
            });
            @endif
        });
    </script>
@endsection
