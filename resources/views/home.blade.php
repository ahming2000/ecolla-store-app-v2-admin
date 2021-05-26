@extends('layouts.app')

@section('title')
    仪表板
@endsection

@section('extraStyle')
    <link href="{{ asset('css/home_styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" />
@endsection

@section('extraScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js" integrity="sha512-FJ2OYvUIXUqCcPf1stu+oTBlhn54W0UisZB/TNrZaVMHHhYvLBV9jMbvJYtvDe5x/WVaoXZ6KB+Uqe5hT2vlyA==" crossorigin="anonymous" defer></script>
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

            {{ var_dump($date_option_arr) }}

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

                                    <form action="{{ url('/home') }}" method="GET" id="day_form">
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="day">請選日期: </label>
                                            </div>
                                            <div class="col-3">
                                                <select class="search_select" name="day" form="day_form">
                                                    <option value="">請輸入日期...</option>
                                                    @foreach ($date_option_arr as $date_str)
                                                        <option value="{{ $date_str }}">{{ $date_str }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col"><button class="btn btn-success" type="submit" name="type"
                                                                     value="daily">提交</button></div>

                                        </div>
                                    </form>

                                    <div class="container">
                                        <div class="row mb-3">
                                            <div class="col-3">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="rotate">
                                                            <i class="fa fa-user fa-5x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase" >訂單數量</h6>
                                                        <div class="display_card">{{ sizeof($daily_date_arr) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="rotate">
                                                            <i class="fa fa-list fa-4x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase">產品數量</h6>
                                                        <div class="display_card">{{ $daily_product_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="rotate">
                                                            <i class="fa fa-twitter fa-5x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase">总销售收入</h6>
                                                        <div class="display_card">RM {{ number_format($daily_product_sales_revenue, 2) }}</div>
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
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>單品價錢</th>
                                                                <th>數量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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

                                    <form action="{{ url('/home') }}" method="GET" id="week_form">
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="week">請選星期: </label>
                                            </div>
                                            <div class="col-5">
                                                <select class="search_select" name="week" form="week_form">
                                                    <option value="">請輸入星期...</option>
                                                    @foreach ($week_option_arr as $week_str)
                                                        <option value="{{ $week_str }}">{{ $week_str }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col"><button class="btn btn-success" type="submit" name="type"
                                                                     value="weekly">提交</button></div>

                                        </div>
                                    </form>

                                    <div class="container">
                                        <div class="row mb-3">
                                            <div class="col-3">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="rotate">
                                                            <i class="fa fa-user fa-5x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase" >訂單數量</h6>
                                                        <div class="display_card">{{ sizeof($week_arr) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="rotate">
                                                            <i class="fa fa-list fa-4x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase">產品數量</h6>
                                                        <div class="display_card">{{ $week_product_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="rotate">
                                                            <i class="fa fa-twitter fa-5x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase">总销售收入</h6>
                                                        <div class="display_card">RM {{ number_format($week_product_sales_revenue, 2) }}</div>
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
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>單品價錢</th>
                                                                <th>數量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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

                                    <form action="{{ url('/home') }}" method="GET" id="month_form">
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="month">請選月份: </label>
                                            </div>
                                            <div class="col-3">
                                                <select class="search_select" name="month" form="month_form">
                                                    <option value="">請輸入月份...</option>
                                                    @foreach ($month_option_arr as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col"><button class="btn btn-success" type="submit" name="type"
                                                value="monthly">提交</button></div>
                                        </div>
                                    </form>

                                    <div class="container">
                                        <div class="row mb-3">
                                            <div class="col-3">
                                                <div class="card card-inverse card-success">
                                                    <div class="card-block bg-success">
                                                        <div class="rotate">
                                                            <i class="fa fa-user fa-5x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase" >訂單數量</h6>
                                                        <div class="display_card">{{ sizeof($month_arr) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="card card-inverse card-danger">
                                                    <div class="card-block bg-danger">
                                                        <div class="rotate">
                                                            <i class="fa fa-list fa-4x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase">產品數量</h6>
                                                        <div class="display_card">{{ $month_product_count }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card card-inverse card-info">
                                                    <div class="card-block bg-info">
                                                        <div class="rotate">
                                                            <i class="fa fa-twitter fa-5x"></i>
                                                        </div>
                                                        <h6 class="text-uppercase">总销售收入</h6>
                                                        <div class="display_card">RM {{ number_format($month_product_sales_revenue, 2) }}</div>
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
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>名字</th>
                                                                <th>單品價錢</th>
                                                                <th>數量</th>
                                                                <th>小计</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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
        $(function() {
            //Selectize JS
            $(".search_select").selectize();

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
