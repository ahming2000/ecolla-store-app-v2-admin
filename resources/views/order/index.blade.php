@extends('layouts.app')

@section('title')
    订单查看
@endsection

@section('extraStyle')
    <style>
        .order-attr-value {
            color: green;
            font-weight: bold;
        }

        .order-status-success {
            color: green;
        }

        .order-status-fail {
            color: orange;
        }

        .order-status {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <main class="container">

        @if(session()->has('message'))
            <div class="alert alert-info text-center" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-6 col-sm-12">
                <div style="font-size: 40px; font-weight: bolder">订单查看</div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="row text-right mb-2">
                    <div class="col-12">
                        <select name="paginate" id="paginateSelector" class="custom-select custom-select-sm shadow">
                            <option value="5" {{ @$_GET['paginate'] == 5 ? "selected" : "" }}>一页显示5个订单</option>
                            <option value="10" {{ @$_GET['paginate'] == 10 ? "selected" : "" }}>一页显示10个订单</option>
                            <option value="20" {{ @$_GET['paginate'] == 20 ? "selected" : "" }}>一页显示20个订单</option>
                            <option value="30" {{ @$_GET['paginate'] == 30 ? "selected" : "" }}>一页显示30个订单</option>
                            <option value="50" {{ @$_GET['paginate'] == 50 ? "selected" : "" }}>一页显示50个订单</option>
                            <option value="80" {{ @$_GET['paginate'] == 80 ? "selected" : "" }}>一页显示80个订单</option>
                            <option value="100" {{ @$_GET['paginate'] == 100 ? "selected" : "" }}>一页显示100个订单</option>
                        </select>
                    </div>
                </div>
                <div class="row text-right mb-2">
                    <div class="col-8">
                        <input type="date"
                               class="form-control form-control-sm shadow"
                               name="created_at"
                               id="dateSelector"
                               value="{{ $_GET['created_at'] ?? '' }}">
                    </div>
                    <div class="col-4">
                        <form action="{{ url('/order') }}" method="get">
                            @if(isset($_GET['paginate']))
                                <input type="hidden" name="paginate" value="{{ $_GET['paginate'] }}">
                            @endif
                            @if(isset($_GET['mode']))
                                <input type="hidden" name="mode" value="{{ $_GET['mode'] }}">
                            @endif
                            <button type="submit" class="btn btn-primary btn-sm m-0 w-100">
                                <i class="icofont icofont-trash"></i> 重置
                            </button>
                        </form>
                    </div>
                </div>
                <div class="row text-right mb-2">
                    <div class="col-12">
                        <select class="custom-select custom-select-sm shadow" name="mode" id="modeSelector">
                            <option value="">全部订单模式</option>
                            <option value="pickup" {{ @$_GET['mode'] == 'pickup' ? "selected" : "" }}>预购取货</option>
                            <option value="delivery" {{ @$_GET['mode'] == 'delivery' ? "selected" : "" }}>外送</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            @foreach($orders as $order)
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            状态
                                        </div>
                                        <div
                                            class="col-12 order-status {{ $order->status == 'canceled' || $order->status == 'refunded' ? 'order-status-fail' : '' }} {{ $order->status == 'completed' ? 'order-status-success' : '' }}">
                                            {{ $order->getStatusDesc() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            付款方式
                                        </div>
                                        <div class="col-12 order-attr-value">
                                            {{ $order->payment_method }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            编号
                                        </div>
                                        <div class="col-12 order-attr-value">
                                            {{ $order->code }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            商品数量
                                        </div>
                                        <div class="col-12 order-attr-value">
                                            {{ sizeof($order->orderItems->toArray()) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            总计
                                        </div>
                                        <div class="col-12 order-attr-value">
                                            RM{{ number_format($order->getSubtotal(), 2, '.', '') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            日期
                                        </div>
                                        <div class="col-12 order-attr-value">
                                            {{ $order->getOrderCreateDateTime() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            种类
                                        </div>
                                        <div class="col-12 order-attr-value">
                                            {{ $order->getOrderModeLabel() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                @if(auth()->user()->hasAccess('order_update'))
                                    <div class="col-6 text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-primary w-100 ml-0"
                                                onclick="window.location.href = '{{ url('/order/' . $order->id) }}'">
                                            <i class="icofont icofont-search-document"></i> 订单详情
                                        </button>
                                    </div>
                                @endif
                                @if(auth()->user()->hasAccess('order_item_view'))
                                    <div class="col-6 text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-info w-100 ml-0"
                                                onclick="window.location.href = '{{ url('/order/' . $order->id . '/item') }}'">
                                            <i class="icofont icofont-align-left"></i> 订单商品
                                        </button>
                                    </div>
                                @endif
                                @if(auth()->user()->hasAccess('order_receipt_view'))
                                    <div class="col-6 text-center">
                                        <a class="btn btn-sm btn-secondary w-100 ml-0"
                                           href="{{ $order->receipt_image }}"
                                           target="_blank">
                                            <i class="icofont icofont-picture"></i> 收据查看
                                        </a>
                                    </div>
                                @endif
                                @if(auth()->user()->hasAccess('order_invoice_download'))
                                    <div class="col-6 text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-secondary w-100 ml-0"
                                                onclick="window.location.href = '{{ url('/order/' . $order->id . '/pdf') }}'">
                                            <i class="icofont icofont-download"></i> 订单下载
                                        </button>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </main>
@endsection

@section('extraScriptEnd')
    <script>
        function viewReceipt(source) {
            let url = source.value;
            window.open(url, 'Image', 'width=400px,height=400px,resizable=1');
        }

        $(document).ready(function () {
            $('#paginateSelector').on('change', function () {
                window.location.href = "/order{!! $params['paginate'] !!}paginate=" + $('#paginateSelector option:selected').val();
            });
            $('#dateSelector').on('change', function () {
                window.location.href = "/order{!! $params['created_at'] !!}created_at=" + $('#dateSelector').val();
            });
            $("#modeSelector").on("change", function () {
                window.location.href = "/order{!! $params['mode'] !!}mode=" + $('#modeSelector option:selected').val();
            });
        });
    </script>
@endsection
