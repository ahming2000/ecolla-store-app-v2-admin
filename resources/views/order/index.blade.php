@extends('layouts.app')

@section('title')
    订单查看
@endsection

@section('content')
    <main class="container">

        @if(session()->has('message'))
            <div class="alert alert-info text-center" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div style="font-size: 40px; font-weight: bolder">订单查看</div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="row text-right mb-2">
                    <div class="col-12">
                        <select name="paginate" id="paginateSelector" class="custom-select custom-select-sm">
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
                               class="form-control form-control-sm"
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
                        <select class="custom-select custom-select-sm" name="mode" id="modeSelector">
                            <option value="">全部订单模式</option>
                            <option value="pickup" {{ @$_GET['mode'] == 'pickup' ? "selected" : "" }}>预购取货</option>
                            <option value="delivery" {{ @$_GET['mode'] == 'delivery' ? "selected" : "" }}>外送</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


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
                            <button class="btn btn-primary btn-sm" onclick="viewReceipt(this)"
                                    value="{{ url($order->receipt_image) }}">查看订单收据
                            </button>
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
                                    <a class="btn btn-primary btn-sm"
                                       href="{{ url('/order/' . $order->id . '/item') }}">显示订单物品</a>
                                </div>
                            </div>
                        </td>
                        <td class="p-0 h-100">
                            <div class="d-flex p-2 align-items-center justify-content-center h-100">
                                <div class="d-flex flex-sm-column align-items-center">
                                    <a class="btn btn-primary btn-sm m-2" href="{{ url('/order/' . $order->id) }}">更改订单状态</a>
                                    <a class="btn btn-primary btn-sm m-2"
                                       href="{{ URL::to('/order/' . $order->id . '/pdf') }}">下载订单收据</a>
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
