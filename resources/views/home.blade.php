@extends('layouts.app')

@section('title')
    仪表板
@endsection

@section('extraStyle')
    <link href="{{ asset('css/home_styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.ui.datepicker.monthyearpicker.css') }}" />
@endsection

@section('extraScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js" integrity="sha512-FJ2OYvUIXUqCcPf1stu+oTBlhn54W0UisZB/TNrZaVMHHhYvLBV9jMbvJYtvDe5x/WVaoXZ6KB+Uqe5hT2vlyA==" crossorigin="anonymous" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/jquery.ui.datepicker.monthyearpicker.js') }}"></script>
    <script src="{{ asset('js/weekPicker.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">{{ __('订单仪表板') }}</div>

            {{-- <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{ __('You are logged in!') }}
            </div> --}}

            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <nav class="nav-justified">
                                <div class="nav nav-tabs " id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link" id="pop1-tab" data-toggle="tab" href="#pop1"
                                        role="tab" aria-controls="pop1" aria-selected="true" onclick="update_chart('timestamp_sales_chart')">Daily</a>
                                    <a class="nav-item nav-link" id="pop2-tab" data-toggle="tab" href="#pop2"
                                        role="tab" aria-controls="pop2" aria-selected="false" onclick="update_chart('daily_sales_chart')">Weekly</a>
                                    <a class="nav-item nav-link" id="pop3-tab" data-toggle="tab" href="#pop3"
                                        role="tab" aria-controls="pop3" aria-selected="false" onclick="update_chart('weekly_sales_chart')">Monthly</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade" id="pop1" role="tabpanel"
                                    aria-labelledby="pop1-tab">
                                    <div class="pt-3"></div>

                                    <div class="container mb-3">
                                        <form action="{{ url('/home') }}" method="GET" id="day_form">
                                            <div class="row">
                                                <div class="form_small_col">
                                                    <label for="day">請選日期: </label>
                                                </div>
                                                <div class="form_large_col">
                                                    <input type="text" id="datePicker" name="day" class="form-control" form="day_form" required/>
                                                </div>
                                                <div class="form_button_col">
                                                    <button class="btn btn-success" type="submit" name="type" value="daily">提交</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="container">
                                        <div class="row mb-3">
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="display_card_header">訂單數量</div>
                                                        <div class="display_card_text">{{ $daily_order_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="display_card_header">產品數量</div>
                                                        <div class="display_card_text">{{ $daily_product_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="display_card_header">总销售收入</div>
                                                        <div class="display_card_text">RM {{ number_format($daily_product_sales_revenue, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-warning">
                                                        <div class="display_card_header">退款数量</div>
                                                        <div class="display_card_text">RM {{ number_format($canceled_daily_product_sales_revenue, 2)}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="half_width p-3">
                                                <div class="border border-danger border_graph_css">
                                                    <div id="timestamp_sales_chart" class="graph_css"></div>
                                                </div>
                                            </div>
                                            <div class="half_width p-3">
                                                <div class="border border-primary border_table_css">
                                                    <table class="table table-striped">
                                                        <thead class="item_table_header">
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>單品價錢</th>
                                                                <th>數量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="item_table_body">
                                                            @foreach ($daily_product_arr as $product)
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

                                    <div class="container mb-3">
                                        <form action="{{ url('/home') }}" method="GET" id="week_form">
                                            <div class="row">
                                                <div class="form_small_col">
                                                    <label for="week">請選星期: </label>
                                                </div>
                                                <div class="form_large_col">
                                                    <input type="text" id="weekPicker" name="week" class="form-control" form="week_form" required/>
                                                </div>
                                                <div class="form_button_col">
                                                    <button class="btn btn-success" type="submit" name="type" value="weekly">提交</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="container">
                                        <div class="row mb-3">
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="display_card_header">訂單數量</div>
                                                        <div class="display_card_text">{{ $week_order_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="display_card_header">產品數量</div>
                                                        <div class="display_card_text">{{ $week_product_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="display_card_header">总销售收入</div>
                                                        <div class="display_card_text">RM {{ number_format($week_product_sales_revenue, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-warning">
                                                        <div class="display_card_header">退款数量</div>
                                                        <div class="display_card_text">RM {{ number_format( $canceled_week_product_sales_revenue, 2)}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="half_width p-3">
                                                <div class="border border-danger border_graph_css">
                                                    <div id="daily_sales_chart" class="graph_css"></div>
                                                </div>
                                            </div>
                                            <div class="half_width p-3">
                                                <div class="border border-primary border_table_css">
                                                    <table class="table table-striped">
                                                        <thead class="item_table_header">
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>單品價錢</th>
                                                                <th>數量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="item_table_body">
                                                            @foreach ($week_product_arr as $product)
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

                                    <div class="container mb-3">
                                        <form action="{{ url('/home') }}" method="GET" id="month_form">
                                            <div class="row">
                                                <div class="form_small_col">
                                                    <label for="month">請選月份: </label>
                                                </div>
                                                <div class="form_large_col">
                                                    <input type="text" id="monthPicker" name="month" class="form-control" form="month_form"/>
                                                </div>
                                                <div class="form_button_col">
                                                    <button class="btn btn-success" type="submit" name="type" value="monthly">提交</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="container">
                                        <div class="row mb-3">
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="display_card_header">訂單數量</div>
                                                        <div class="display_card_text">{{ $month_order_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_small_col">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="display_card_header">產品數量</div>
                                                        <div class="display_card_text">{{ $month_product_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="display_card_header">总销售收入</div>
                                                        <div class="display_card_text">RM {{ number_format($month_product_sales_revenue, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card_large_col">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-warning">
                                                        <div class="display_card_header">退款数量</div>
                                                        <div class="display_card_text">RM {{ number_format($canceled_month_product_sales_revenue, 2)}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="half_width p-3">
                                                <div class="border border-danger border_graph_css">
                                                    <div id="weekly_sales_chart" class="graph_css"></div>
                                                </div>
                                            </div>
                                            <div class="half_width p-3">
                                                <div class="border border-primary border_table_css">
                                                    <table class="table table-striped">
                                                        <thead class="item_table_header">
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>單品價錢</th>
                                                                <th>數量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="item_table_body">
                                                            @foreach ($month_product_arr as $product)
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
        <p id="date_option_str">{{ $daily_option_str }}</p>
        <p id="week_option_str">{{ $week_option_str }}</p>
        <p id="month_option_str">{{ $month_option_str }}</p>

        <p id="month_1">{{ $month_graph_arr[0] }}</p>
        <p id="month_2">{{ $month_graph_arr[1] }}</p>
        <p id="month_3">{{ $month_graph_arr[2] }}</p>

        <p id="week_1">{{ $week_graph_arr[0] }}</p>
        <p id="week_2">{{ $week_graph_arr[1] }}</p>
        <p id="week_3">{{ $week_graph_arr[2] }}</p>

        <p id="daily_1">{{ $daily_graph_arr[0] }}</p>
        <p id="daily_2">{{ $daily_graph_arr[1] }}</p>
        <p id="daily_3">{{ $daily_graph_arr[2] }}</p>

        <p id="tab_active">{{ $tab_active }}</p>
    </div>
@endsection

@section('extraScriptEnd')
    <script>
        let weekly_sales_chart, daily_sales_chart, timestamp_sales_chart;
        let date_option_arr = [],
            week_option_arr = [],
            month_option_hashmap = {},
            month_option_year_hashmap = {};
        let month_txt_arr = "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" ");
        $(function() {
            //Controls Which Tab is Active
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

            // Get Options
            // Daily
            let date_option_str = $("#date_option_str").html();
            let date_option_arr = date_option_str.split(" ");
            // Weekly
            let week_option_str = $("#week_option_str").html();
            let w_arr = week_option_str.split(" ");
            for (let week of w_arr) {
                let tmp_arr = week.split("-");
                let tmp_year = parseInt(tmp_arr[0]),
                    tmp_week = parseInt(tmp_arr[1]);
                week_option_arr = week_option_arr.concat(getDateOfISOWeekArr(tmp_week, tmp_year));
            }
            // Monthly
            let month_option_str = $("#month_option_str").html();
            let m_arr = month_option_str.split(",");
            for (let str of m_arr) {
                let tmp_arr = str.split("/");
                let tmp_year = tmp_arr[0];
                if (month_option_hashmap[tmp_year])
                    month_option_hashmap[tmp_year] = month_option_hashmap[tmp_year].concat(tmp_arr[1].split(" "));
                else
                    month_option_hashmap[tmp_year] = tmp_arr[1].split(" ");
            }

            // Get All Year From Month
            let month_option_year = Object.keys(month_option_hashmap);
            for (let yr of month_option_year) {
                let ind = parseInt(parseInt(yr) / 10, 10) * 10;
                if (month_option_year_hashmap[ind - 1])
                    month_option_year_hashmap[ind - 1].push(parseInt(yr));
                else
                    month_option_year_hashmap[ind - 1] = [parseInt(yr)];
            }

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

            weekly_sales_chart = new CanvasJS.Chart("weekly_sales_chart", {
                title: {
                    text: "每周訂單分析"
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
                    name: "產品數量",
                    color: "rgba(194, 70, 66, 0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "訂單數量",
                    color: "rgba(0,75,141,0.7)",
                    showInLegend: true,
                    axisYIndex: 1,
                    dataPoints: []
                }]
            });

            daily_sales_chart = new CanvasJS.Chart("daily_sales_chart", {
                title: {
                    text: "每日訂單分析"
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
                    name: "產品數量",
                    color: "rgba(194, 70, 66, 0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "訂單數量",
                    color: "rgba(0,75,141,0.7)",
                    showInLegend: true,
                    axisYIndex: 1,
                    dataPoints: []
                }]
            });

            timestamp_sales_chart = new CanvasJS.Chart("timestamp_sales_chart", {
                title: {
                    text: "訂單时间戳记分析"
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
                    name: "產品數量",
                    color: "rgba(194, 70, 66, 0.6)",
                    axisYIndex: 0,
                    showInLegend: true,
                    dataPoints: []
                }, {
                    type: "area",
                    name: "訂單數量",
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
@endsection
