@inject('helper', \App\Util\ViewHelper::class)

@extends('layouts.app')

@section('title')
    订单查看
@endsection

@section('style')
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
                <div class="h1">
                    订单查看
                </div>
            </div>

            <div class="col-md-6 col-sm-12">

                <form action="{{ url('/order') }}" method="get" id="filterForm">
                    <select name="paginate" id="paginateSelector" class="form-select shadow mb-2">
                        <option value="25" {{ $helper->paramSelected('paginate', '25') }}>一页显示25个订单</option>
                        <option value="50" {{ $helper->paramSelected('paginate', '50') }}>一页显示50个订单</option>
                        <option value="75" {{ $helper->paramSelected('paginate', '75') }}>一页显示75个订单</option>
                        <option value="100" {{ $helper->paramSelected('paginate', '100') }}>一页显示100个订单</option>
                    </select>

                    <div class="d-flex justify-content-between mb-2">
                        <div class="flex-grow-1 me-2">
                            <input type="date"
                                   class="form-control shadow"
                                   name="created_at"
                                   id="dateSelector"
                                   value="{{ $_GET['created_at'] ?? '' }}">
                        </div>

                        <div>
                            <button type="button" class="btn btn-primary" id="dateFilterResetButton">
                                <i class="icofont icofont-trash"></i> 重置
                            </button>
                        </div>
                    </div>

                    <select class="form-select shadow" name="mode" id="modeSelector">
                        <option value="">全部订单模式</option>
                        <option value="pickup" {{ $helper->paramSelected('mode', 'pickup') }}>预购取货</option>
                        <option value="delivery" {{ $helper->paramSelected('mode', 'delivery') }}>外送</option>
                    </select>
                </form>

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
                                    <div class="col-6 text-center mb-2">
                                        <button type="button"
                                                class="btn btn-sm btn-primary w-100 ms-0"
                                                onclick="window.location.href = '{{ url('/order/' . $order->id) }}'">
                                            <i class="icofont icofont-search-document"></i> 订单详情
                                        </button>
                                    </div>
                                @endif
                                @if(auth()->user()->hasAccess('order_item_view'))
                                    <div class="col-6 text-center mb-2">
                                        <button type="button"
                                                class="btn btn-sm btn-primary w-100 ms-0"
                                                onclick="window.location.href = '{{ url('/order/' . $order->id . '/item') }}'">
                                            <i class="icofont icofont-align-left"></i> 订单商品
                                        </button>
                                    </div>
                                @endif
                                @if(auth()->user()->hasAccess('order_receipt_view'))
                                    <div class="col-6 text-center mb-2">
                                        <a class="btn btn-sm btn-secondary w-100 ms-0"
                                           href="{{ $order->receipt_image }}"
                                           target="_blank">
                                            <i class="icofont icofont-picture"></i> 收据查看
                                        </a>
                                    </div>
                                @endif
                                @if(auth()->user()->hasAccess('order_invoice_download'))
                                    <div class="col-6 text-center mb-2">
                                        <button type="button"
                                                class="btn btn-sm btn-secondary w-100 ms-0"
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

        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </main>
@endsection

@section('script')
    <script>
        function viewReceipt(source) {
            let url = source.value;
            window.open(url, 'Image', 'width=400px,height=400px,resizable=1');
        }

        $(document).ready(function () {
            $('#paginateSelector').on('change', function () {
                $('#filterForm').submit();
            });
            $('#dateSelector').on('change', function () {
                $('#filterForm').submit();
            });
            $("#modeSelector").on("change", function () {
                $('#filterForm').submit();
            });

            $('#dateFilterResetButton').on('click', function () {
                $('#dateSelector').val("");
                $('#filterForm').submit();
            });
        });
    </script>
@endsection
