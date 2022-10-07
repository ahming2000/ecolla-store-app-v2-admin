@extends('layouts.app')

@section('title')
    仪表板
@endsection

@section('style')

    <style rel="stylesheet">


        body,
        html {
            height: 100%;
        }


        /* workaround modal-open padding issue */

        body.modal-open {
            padding-right: 0 !important;
        }

        .card {
            overflow: hidden;
        }

        .card-block .rotate {
            z-index: 8;
            float: right;
            height: 100%;
        }

        .card-block .rotate i {
            color: rgba(20, 20, 20, 0.15);
            position: absolute;
            left: 0;
            left: auto;
            right: -10px;
            bottom: 0;
            display: block;
            -webkit-transform: rotate(-44deg);
            -moz-transform: rotate(-44deg);
            -o-transform: rotate(-44deg);
            -ms-transform: rotate(-44deg);
            transform: rotate(-44deg);
        }

        .nav-tabs .nav-link.active {
            font-weight: bold;
            background-color: transparent;
            border-bottom: 3px solid #dd0000;
            border-right: none;
            border-left: none;
            border-top: none;
        }

        .card_small_col,
        .card_large_col {
            width: 45%;
            padding-left: 10px;
            padding-right: 10px;
        }

        .card_large_col {
            padding-top: 10px;
        }

        .form_small_col,
        .form_large_col,
        .form_button_col {
            width: 100%;
        }

        .form_button_col .btn {
            margin-left: 0px;
        }

        .display_card_text {
            font-size: 12px;
        }

        .display_card_header {
            font-size: 16px;
        }

        .half_width {
            width: 100%;
        }

        .border_graph_css {
            height: 400px;
        }

        .item_table tr th:first-child,
        .item_table tr td:first-child {
            width: 6em;
            word-break: break-all;
        }

        .item_table tr th:last-child,
        .item_table tr td:last-child {
            display: none;
        }

        @media only screen and (max-width: 319px) {
            .order_table tr th:nth-child(1),
            .order_table tr td:nth-child(1) {
                width: 6em;
                word-break: break-all;
            }
            .order_table tr th:first-child~th,
            .order_table tr td:first-child~td {
                display: none;
            }
        }

        @media only screen and (min-width: 320px) and (max-width: 449px) {
            .order_table tr th:nth-child(1),
            .order_table tr th:nth-child(2),
            .order_table tr td:nth-child(1),
            .order_table tr td:nth-child(2) {
                width: 8em;
                word-break: break-all;
            }
            .order_table tr th:nth-child(2)~th,
            .order_table tr td:nth-child(2)~td {
                display: none;
            }
        }

        @media only screen and (min-width: 450px) and (max-width: 768px) {
            .order_table tr th:nth-child(1),
            .order_table tr td:nth-child(1) {
                width: 8em;
                word-break: break-all;
            }
            .order_table tr th:nth-child(3)~th,
            .order_table tr td:nth-child(3)~td {
                display: none;
            }
        }


        /* Tablet */

        @media only screen and (min-width:450px) and (max-width:1199px) {
            .display_card_text {
                font-size: 1.5rem;
            }
            .border_graph_css {
                height: 400px;
            }
            .item_table tr th:first-child,
            .item_table tr td:first-child {
                width: 12em;
                word-break: break-all;
            }
            .item_table tr th:last-child,
            .item_table tr td:last-child {
                display: block;
            }
        }


        /* Desktop */

        @media (min-width: 1200px) {
            .form_small_col {
                width: 10%;
            }
            .form_large_col {
                width: 50%;
            }
            .form_button_col {
                width: 15%;
                position: relative;
                left: 5px;
            }
            .card_small_col {
                width: 15%;
                padding-left: 10px;
                padding-right: 10px;
            }
            .card_large_col {
                width: 30%;
                padding-left: 10px;
                padding-right: 10px;
                padding-top: 0px;
            }
            .display_card_header {
                font-size: 20px;
            }
            .display_card_text {
                font-size: 36px;
            }
            .half_width {
                width: 50%
            }
            .border_graph_css,
            .border_table_css {
                height: 450px;
            }
            .item_table tr th:first-child,
            .item_table tr td:first-child {
                width: 12em;
                word-break: break-all;
            }
            .item_table tr th:last-child,
            .item_table tr td:last-child {
                display: block;
            }
        }
    </style>
@endsection

@section('content')
    @if(auth()->user()->hasAccess('dashboard_view'))
    <div class="container">
        <div class="card">
            <div class="card-header">订单仪表板</div>

            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <nav class="nav-justified">
                                <div class="nav nav-tabs " id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link" id="pop1-tab" data-bs-toggle="tab" href="#pop1"
                                        role="tab" aria-controls="pop1" aria-selected="true" onclick="update_chart('timestamp_sales_chart')">每日</a>
                                    <a class="nav-item nav-link" id="pop2-tab" data-bs-toggle="tab" href="#pop2"
                                        role="tab" aria-controls="pop2" aria-selected="false" onclick="update_chart('daily_sales_chart')">每周</a>
                                    <a class="nav-item nav-link" id="pop3-tab" data-bs-toggle="tab" href="#pop3"
                                        role="tab" aria-controls="pop3" aria-selected="false" onclick="update_chart('weekly_sales_chart')">每月</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade" id="pop1" role="tabpanel"
                                    aria-labelledby="pop1-tab">
                                    <div class="pt-3"></div>

                                    <div class="container">
                                        <div class="container mb-3">
                                            <form action="{{ url('/home') }}" method="GET" id="day_form">
                                                <div class="row">
                                                    <div class="form_small_col">
                                                        <label for="day">请选日期: </label>
                                                    </div>
                                                    <div class="form_large_col">
                                                        <input type="text" id="datePicker" name="day" class="form-control" form="day_form" autocomplete="off" required/>
                                                    </div>
                                                    <div class="form_button_col">
                                                        <button class="btn btn-sm btn-success" type="submit" name="type" value="daily">提交</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="display_card_header">订单数量</div>
                                                        <div class="display_card_text">{{ $daily_info_arr["orderCount"] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="display_card_header">产品数量</div>
                                                        <div class="display_card_text">{{ $daily_info_arr["productCount"] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="display_card_header">总销售收入</div>
                                                        <div class="display_card_text">RM {{ number_format($daily_info_arr["totalSalesRevenue"], 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-warning">
                                                        <div class="display_card_header">退款数量</div>
                                                        <div class="display_card_text">RM {{ number_format($daily_info_arr["canceledSalesRevenue"], 2)}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border border-success border_order_table">
                                            <table class="table table-striped order_table">
                                                <thead>
                                                    <tr class="table-success">
                                                        <th>订单代码</th>
                                                        <th>订单状态</th>
                                                        <th>订购时间</th>
                                                        <th>支付方法</th>
                                                        <th>产品数量</th>
                                                        <th>小计</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($daily_info_arr["orderDetailInfo"] as $orderDetailInfo)
                                                        <tr>
                                                            <td>{{ $orderDetailInfo["orderCode"] }}</td>
                                                            <td>{{ $orderDetailInfo["orderStatus"] }}</td>
                                                            <td>{{ $orderDetailInfo["timeStamp"] }}</td>
                                                            <td>{{ $orderDetailInfo["paymentMethod"] }}</td>
                                                            <td>{{ $orderDetailInfo["productCount"] }}</td>
                                                            <td>RM {{ number_format($orderDetailInfo["subTotal"], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="half_width p-3">
                                                <div class="border border-danger border_graph_css">
                                                    <div id="timestamp_sales_chart" class="graph_css"></div>
                                                </div>
                                            </div>
                                            <div class="half_width p-3">
                                                <div class="border border-primary border_table_css">
                                                    <table class="table table-striped item_table">
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>单品价钱</th>
                                                                <th>数量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($daily_info_arr["productArr"] as $product)
                                                            <tr>
                                                                <td>{{ $product->name }}</td>
                                                                <td>RM {{ number_format($product->price, 2) }}</td>
                                                                <td>{{ $product->quantity }}</td>
                                                                <td>RM {{ number_format($product->quantity * $product->price, 2) }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="pop2" role="tabpanel" aria-labelledby="pop2-tab">
                                    <div class="pt-3"></div>

                                    <div class="container">
                                        <div class="container mb-3">
                                            <form action="{{ url('/home') }}" method="GET" id="week_form">
                                                <div class="row">
                                                    <div class="form_small_col">
                                                        <label for="week">请选星期: </label>
                                                    </div>
                                                    <div class="form_large_col">
                                                        <input type="text" id="weekPicker" name="week" class="form-control" form="week_form" autocomplete="off" required/>
                                                    </div>
                                                    <div class="form_button_col">
                                                        <button class="btn btn-sm btn-success" type="submit" name="type" value="weekly">提交</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="display_card_header">订单数量</div>
                                                        <div class="display_card_text">{{ $week_info_arr["orderCount"] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="display_card_header">产品数量</div>
                                                        <div class="display_card_text">{{ $week_info_arr["productCount"] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="display_card_header">总销售收入</div>
                                                        <div class="display_card_text">RM {{ number_format($week_info_arr["totalSalesRevenue"], 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-warning">
                                                        <div class="display_card_header">退款数量</div>
                                                        <div class="display_card_text">RM {{ number_format( $week_info_arr["canceledSalesRevenue"], 2)}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border border-success border_order_table">
                                            <table class="table table-striped order_table">
                                                <thead>
                                                    <tr class="table-success">
                                                        <th>订单代码</th>
                                                        <th>订单状态</th>
                                                        <th>订购时间</th>
                                                        <th>支付方法</th>
                                                        <th>产品数量</th>
                                                        <th>小计</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($week_info_arr["orderDetailInfo"] as $orderDetailInfo)
                                                        <tr>
                                                            <td>{{ $orderDetailInfo["orderCode"] }}</td>
                                                            <td>{{ $orderDetailInfo["orderStatus"] }}</td>
                                                            <td>{{ $orderDetailInfo["timeStamp"] }}</td>
                                                            <td>{{ $orderDetailInfo["paymentMethod"] }}</td>
                                                            <td>{{ $orderDetailInfo["productCount"] }}</td>
                                                            <td>RM {{ number_format($orderDetailInfo["subTotal"], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="half_width p-3">
                                                <div class="border border-danger border_graph_css">
                                                    <div id="daily_sales_chart" class="graph_css"></div>
                                                </div>
                                            </div>
                                            <div class="half_width p-3">
                                                <div class="border border-primary border_table_css">
                                                    <table class="table table-striped item_table">
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>单品价钱</th>
                                                                <th>数量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($week_info_arr["productArr"] as $product)
                                                            <tr>
                                                                <td>{{ $product->name }}</td>
                                                                <td>RM {{ number_format($product->price, 2) }}</td>
                                                                <td>{{ $product->quantity }}</td>
                                                                <td>RM {{ number_format($product->quantity * $product->price, 2) }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="pop3" role="tabpanel" aria-labelledby="pop3-tab">
                                    <div class="pt-3"></div>

                                    <div class="container">
                                        <div class="container mb-3">
                                            <form action="{{ url('/home') }}" method="GET" id="month_form">
                                                <div class="row">
                                                    <div class="form_small_col">
                                                        <label for="month">请选月份: </label>
                                                    </div>
                                                    <div class="form_large_col">
                                                        <input type="text" id="monthPicker" name="month" class="form-control" form="month_form" autocomplete="off" required/>
                                                    </div>
                                                    <div class="form_button_col">
                                                        <button class="btn btn-sm btn-success" type="submit" name="type" value="monthly">提交</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="display_card_header">订单数量</div>
                                                        <div class="display_card_text">{{ $month_info_arr["orderCount"] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="display_card_header">产品数量</div>
                                                        <div class="display_card_text">{{ $month_info_arr["productCount"] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="display_card_header">总销售收入</div>
                                                        <div class="display_card_text">RM {{ number_format($month_info_arr["totalSalesRevenue"], 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-warning">
                                                        <div class="display_card_header">退款数量</div>
                                                        <div class="display_card_text">RM {{ number_format($month_info_arr["canceledSalesRevenue"], 2)}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border border-success border_order_table">
                                            <table class="table table-striped order_table">
                                                <thead>
                                                    <tr class="table-success">
                                                        <th>订单代码</th>
                                                        <th>订单状态</th>
                                                        <th>订购时间</th>
                                                        <th>支付方法</th>
                                                        <th>产品数量</th>
                                                        <th>小计</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($month_info_arr["orderDetailInfo"] as $orderDetailInfo)
                                                        <tr>
                                                            <td>{{ $orderDetailInfo["orderCode"] }}</td>
                                                            <td>{{ $orderDetailInfo["orderStatus"] }}</td>
                                                            <td>{{ $orderDetailInfo["timeStamp"] }}</td>
                                                            <td>{{ $orderDetailInfo["paymentMethod"] }}</td>
                                                            <td>{{ $orderDetailInfo["productCount"] }}</td>
                                                            <td>RM {{ number_format($orderDetailInfo["subTotal"], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="half_width p-3">
                                                <div class="border border-danger border_graph_css">
                                                    <div id="weekly_sales_chart" class="graph_css"></div>
                                                </div>
                                            </div>
                                            <div class="half_width p-3">
                                                <div class="border border-primary border_table_css">
                                                    <table class="table table-striped item_table">
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>单品价钱</th>
                                                                <th>数量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($month_info_arr["productArr"] as $product)
                                                            <tr>
                                                                <td>{{ $product->name }}</td>
                                                                <td>RM {{ number_format($product->price, 2) }}</td>
                                                                <td>{{ $product->quantity }}</td>
                                                                <td>RM {{ number_format($product->quantity * $product->price, 2) }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <div style="display: none">
        <p id="date_selected_str">{{ $daily_info_arr["selectedStr"] }}</p>
        <p id="week_selected_str">{{ $week_info_arr["selectedStr"] }}</p>
        <p id="month_selected_str">{{ $month_info_arr["selectedStr"] }}</p>

        <p id="date_option_str">{{ $daily_info_arr["optionStr"] }}</p>
        <p id="week_option_str">{{ $week_info_arr["optionStr"] }}</p>
        <p id="month_option_str">{{ $month_info_arr["optionStr"] }}</p>

        <p id="daily_1">{{ $daily_info_arr["totalSalesRevenueGraph"] }}</p>
        <p id="daily_2">{{ $daily_info_arr["productCountGraph"] }}</p>
        <p id="daily_3">{{ $daily_info_arr["orderCountGraph"] }}</p>

        <p id="week_1">{{ $week_info_arr["totalSalesRevenueGraph"] }}</p>
        <p id="week_2">{{ $week_info_arr["productCountGraph"] }}</p>
        <p id="week_3">{{ $week_info_arr["orderCountGraph"] }}</p>

        <p id="month_1">{{ $month_info_arr["totalSalesRevenueGraph"] }}</p>
        <p id="month_2">{{ $month_info_arr["productCountGraph"] }}</p>
        <p id="month_3">{{ $month_info_arr["orderCountGraph"] }}</p>

        <p id="tab_active">{{ $tab_active }}</p>
    </div>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/canvasjs-3.4.13/jquery.canvasjs.min.js') }}"></script>

    @if(auth()->user()->hasAccess('dashboard_view'))
    <script>


        // weekPicker.js
        var globalTriggeringElement;
        var globalAdditionalFunction = function() { null; };

        var getDateFromISOWeek = function(ywString, separator) {
            try {
                var ywArray = ywString.split(separator);
                var y = ywArray[0];
                var w = ywArray[1];
                var simple = new Date(y, 0, 1 + (w - 1) * 7);
                var dow = simple.getDay();
                var ISOweekStart = simple;
                if (dow <= 4)
                    ISOweekStart.setDate(simple.getDate() - simple.getDay() + 1);
                else
                    ISOweekStart.setDate(simple.getDate() + 8 - simple.getDay());
                return ISOweekStart;
            } catch (err) {
                console.error("Cannot convert Week into date");
                return new Date();
            }
        };

        var showWeekCalendar = function(triggeringElement, additionalFunction) {
            globalTriggeringElement = triggeringElement;
            globalAdditionalFunction = additionalFunction;
            var prevItem = $(triggeringElement).prev();
            var weekValue = prevItem.val();
            prevItem.datepicker("option", "defaultDate", getDateFromISOWeek(weekValue, '-'));
            prevItem.val(weekValue);
            prevItem.datepicker("show");
        };

        var setWeekCalendar = function(settingElement) {
            var startDate;
            var endDate;
            var selectCurrentWeek = function() {
                window.setTimeout(function() {
                    var activeElement = $("#ui-datepicker-div .ui-state-active");
                    var tdElement = activeElement.parent();
                    var trElement = tdElement.parent();

                    trElement.find("a").addClass("ui-state-active")

                }, 1);
            };

            $(settingElement).datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                showWeek: true,
                firstDay: 1,
                onSelect: function(dateText, inst) {
                    var datepickerValue = $(this).datepicker('getDate');
                    var dateObj = new Date(datepickerValue.getFullYear(), datepickerValue.getMonth(), datepickerValue.getDate());
                    var weekNum = $.datepicker.iso8601Week(dateObj);
                    if (weekNum < 10) {
                        weekNum = "0" + weekNum;
                    }
                    var weekYear = datepickerValue.getFullYear();
                    if (datepickerValue.getMonth() == 11 && weekNum == 01) {
                        weekYear += 1;
                    }
                    var ywString = weekYear + '-' + weekNum;
                    $(this).val(ywString);
                    $(this).prev().html(ywString);
                    startDate = new Date(datepickerValue.getFullYear(), datepickerValue.getMonth(), datepickerValue.getDate() - datepickerValue.getDay());
                    endDate = new Date(datepickerValue.getFullYear(), datepickerValue.getMonth(), datepickerValue.getDate() - datepickerValue.getDay() + 6);
                    selectCurrentWeek();
                    $(this).data('datepicker').inline = true;
                    globalAdditionalFunction(globalTriggeringElement);
                },
                onClose: function() {
                    $(this).data('datepicker').inline = false;
                },
                beforeShow: function() {
                    selectCurrentWeek();
                },
                beforeShowDay: function(datepickerValue) {
                    let cssClass = '';
                    if (datepickerValue >= startDate && datepickerValue <= endDate)
                        cssClass = 'ui-datepicker-current-day';
                    selectCurrentWeek();
                    return enableAllTheseWeeks(datepickerValue, week_option_arr, cssClass);
                },
                onChangeMonthYear: function(year, month, inst) {
                    selectCurrentWeek();
                }
            }).datepicker('widget').addClass('ui-weekpicker');

            $('body').on('mousemove', '#weekPicker .ui-weekpicker .ui-datepicker-calendar tr', function() { $(this).find('td a').addClass('ui-state-hover'); });
            $('body').on('mouseleave', '#weekPicker .ui-weekpicker .ui-datepicker-calendar tr', function() { $(this).find('td a').removeClass('ui-state-hover'); });

            // function for doing something more

        };

        function enableAllTheseWeeks(date, date_arr, cssClass) {
            let sdate = $.datepicker.formatDate('yy-mm-dd', date);
            return [$.inArray(sdate, date_arr) != -1, cssClass];
        }

        var convertToWeekPicker = function(targetElement, fontAwesomeIcon) {
            if (targetElement.prop("tagName") == "INPUT" && (targetElement.attr("type") == "text" || targetElement.attr("type") == "hidden")) {
                var week = targetElement.val();
                $('<span class="displayDate" style="display:none">' + week + '</span>').insertBefore(targetElement);
                if (fontAwesomeIcon) {
                    $('<i class="fa fa-' + fontAwesomeIcon + ' showCalendar" aria-hidden="true" style="cursor:pointer;margin-left: 10px;margin-top: 3px;" onclick="javascript:showWeekCalendar(this)"></i>').insertAfter(targetElement);
                }
                setWeekCalendar(targetElement);
            } else {
                targetElement.replaceWith("<span>ERROR: please control js console</span>");
                console.error("convertToWeekPicker() - ERROR: The target element is not compatible with this conversion, try to use an <input type=\"text\" /> or an <input type=\"hidden\" />");
            }
        };






        let date_option_arr = [],
            week_option_arr = [],
            month_option_hashmap = {},
            month_option_year_hashmap = {},
            month_txt_arr = "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" ");
        $(function() {
            // Update the Input Bar To Show Selected Date, Week and Month
            let date_selected_str = $("#date_selected_str").html(),
                week_selected_str = $("#week_selected_str").html(),
                month_selected_str = $("#month_selected_str").html();

            $("#datePicker").val(date_selected_str);
            $("#weekPicker").val(week_selected_str);
            $("#monthPicker").val(month_selected_str);

            // Controls Which Tab is Active
            let tab_active = $("#tab_active").html();
            switch(tab_active){
                case "daily":
                    $("#pop1").addClass("show active");
                    $("#pop1-tab").addClass("active");
                    break;
                case "weekly":
                    $("#pop2").addClass("show active");
                    $("#pop2-tab").addClass("active");
                    break;
                case "monthly":
                    $("#pop3").addClass("show active");
                    $("#pop3-tab").addClass("active");
                    break;
            }

            let temp = {!! $temp !!};
            // Get Options
            // Daily
            // let date_option_str = $("#date_option_str").html();
            // let date_option_arr = date_option_str.split(" ");
            let date_option_arr = temp.daily;

            // let week_option_str = $("#week_option_str").html();
            // let w_arr = week_option_str.split(" ");
            // for (let week of w_arr) {
            //     let tmp_arr = week.split("-");
            //     let tmp_year = parseInt(tmp_arr[0]),
            //         tmp_week = parseInt(tmp_arr[1]);
            //     week_option_arr = week_option_arr.concat(getDateOfISOWeekArr(tmp_week, tmp_year));
            // }
            week_option_arr = temp.weekly;

            // Monthly
            // let month_option_str = $("#month_option_str").html();
            // let m_arr = month_option_str.split(",");
            // for (let str of m_arr) {
            //     let tmp_arr = str.split("/");
            //     let tmp_year = tmp_arr[0];
            //     if (month_option_hashmap[tmp_year])
            //         month_option_hashmap[tmp_year] = month_option_hashmap[tmp_year].concat(tmp_arr[1].split(" "));
            //     else
            //         month_option_hashmap[tmp_year] = tmp_arr[1].split(" ");
            // }
            month_option_hashmap = temp.monthly;

            // Get All Year From Month
            // let month_option_year = Object.keys(month_option_hashmap);
            // for (let yr of month_option_year) {
            //     let ind = parseInt(parseInt(yr) / 10, 10) * 10;
            //     if (month_option_year_hashmap[ind - 1])
            //         month_option_year_hashmap[ind - 1].push(parseInt(yr));
            //     else
            //         month_option_year_hashmap[ind - 1] = [parseInt(yr)];
            // }
            month_option_year_hashmap = temp.yearly;

            // Date Picker
            $("#datePicker").datepicker({
                dateFormat: "yy-mm-dd",
                beforeShowDay: function(date) {
                    return enableAllTheseDays(date, date_option_arr);
                }
            });

            // Week Picker
            convertToWeekPicker($("#weekPicker"));

            // Month Picker
            $('#monthPicker').datepicker({
                showButtonPanel: true,
                onClose: function(dateText, inst) {
                    let month = $(".ui-datepicker-title .ui-datepicker-month").html() || month_txt_arr[new Date().getMonth()];
                    let year = $(".ui-datepicker-title .ui-datepicker-year").html() || new Date().getFullYear();
                    $(this).val(month.slice(0, 3) + " " + year);
                }
            });

            $("#monthPicker").focus(function() {
                $(".ui-datepicker-calendar").hide();
                $("#ui-datepicker-div").position({
                    my: "left top",
                    at: "left bottom",
                    of: $(this)
                });
            });

            //Canvas JS Graph
            let month_1 = $("#month_1").html(),
                month_2 = $("#month_2").html(),
                month_3 = $("#month_3").html(),
                week_1 = $("#week_1").html(),
                week_2 = $("#week_2").html(),
                week_3 = $("#week_3").html(),
                daily_1 = $("#daily_1").html(),
                daily_2 = $("#daily_2").html(),
                daily_3 = $("#daily_3").html();

            let weekly_sales_chart = new CanvasJS.Chart("weekly_sales_chart", {
                title: {
                    text: "每周订单分析"
                },
                exportEnabled: true,
                animationEnabled: true,
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                axisY: {
                    title: "交易信息",
                    lineColor: "black",
                    tickColor: "black",
                    labelFontColor: "black",
                    titleFontColor: "black"
                },
                data: [{
                    type: "area",
                    name: "总销售收入",
                    color: "rgba(40,175,101,0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "产品数量",
                    color: "rgba(194, 70, 66, 0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "订单数量",
                    color: "rgba(0,75,141,0.7)",
                    showInLegend: true,
                    axisYIndex: 1,
                    dataPoints: []
                }]
            });

            let daily_sales_chart = new CanvasJS.Chart("daily_sales_chart", {
                title: {
                    text: "每日订单分析"
                },
                exportEnabled: true,
                animationEnabled: true,
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                axisY: {
                    title: "交易信息",
                    lineColor: "black",
                    tickColor: "black",
                    labelFontColor: "black",
                    titleFontColor: "black"
                },
                data: [{
                    type: "area",
                    name: "总销售收入",
                    color: "rgba(40,175,101,0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "产品数量",
                    color: "rgba(194, 70, 66, 0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "订单数量",
                    color: "rgba(0,75,141,0.7)",
                    showInLegend: true,
                    axisYIndex: 1,
                    dataPoints: []
                }]
            });

            let timestamp_sales_chart = new CanvasJS.Chart("timestamp_sales_chart", {
                title: {
                    text: "订单时间戳记分析"
                },
                exportEnabled: true,
                animationEnabled: true,
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                axisY: {
                    title: "交易信息",
                    lineColor: "black",
                    tickColor: "black",
                    labelFontColor: "black",
                    titleFontColor: "black"
                },
                data: [{
                    type: "area",
                    name: "总销售收入",
                    color: "rgba(40,175,101,0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "产品数量",
                    color: "rgba(194, 70, 66, 0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "订单数量",
                    color: "rgba(0,75,141,0.7)",
                    showInLegend: true,
                    axisYIndex: 1,
                    dataPoints: []
                }]
            });

            weekly_sales_chart.options.data[0].dataPoints = parseDataPoints(month_1);
            weekly_sales_chart.options.data[1].dataPoints = parseDataPoints(month_2);
            weekly_sales_chart.options.data[2].dataPoints = parseDataPoints(month_3);
            weekly_sales_chart.render();

            daily_sales_chart.options.data[0].dataPoints = parseDataPoints(week_1);
            daily_sales_chart.options.data[1].dataPoints = parseDataPoints(week_2);
            daily_sales_chart.options.data[2].dataPoints = parseDataPoints(week_3);
            daily_sales_chart.render();

            timestamp_sales_chart.options.data[0].dataPoints = parseDataPoints(daily_1);
            timestamp_sales_chart.options.data[1].dataPoints = parseDataPoints(daily_2);
            timestamp_sales_chart.options.data[2].dataPoints = parseDataPoints(daily_3);
            timestamp_sales_chart.render();
        });

        function getDateOfISOWeekArr(w, y) {
            var simple = new Date(y, 0, 1 + (w - 1) * 7);
            var dow = simple.getDay();
            var ISOweekStart = simple;
            if (dow <= 4)
                ISOweekStart.setDate(simple.getDate() - simple.getDay() + 1);
            else
                ISOweekStart.setDate(simple.getDate() + 8 - simple.getDay());
            let arr = [];
            for (let i = 1; i <= 7; i++) {
                let dt = new Date(ISOweekStart);
                dt.setDate(dt.getDate() + i);
                arr.push(dt.toISOString().slice(0, 10))
            }
            return arr;
        }

        function enableAllTheseDays(date, date_arr) {
            let sdate = $.datepicker.formatDate('yy-mm-dd', date)
            return [$.inArray(sdate, date_arr) != -1];
        }

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }

        function parseDataPoints(str) {
            let arr = [];
            let arr_split = str.split("/");
            for (let i = 0; i < arr_split.length; i += 2)
                arr.push(new new_obj(arr_split[i], parseInt(arr_split[i + 1])));
            return arr;
        };

        function new_obj(label, y) {
            this.label = label;
            this.y = y;
        }

        function update_chart(id){
            $(`#${id} canvas`).css("width", "100%");
        }
    </script>
    @endif
@endsection
