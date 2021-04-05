@extends('layouts.app')

@section('title')
    商品管理
@endsection

@section('content')
    <!--Load Table functions from admin_table.js-->
    <script src="{{ asset('js/admin_table.js')}}"></script>

    <style>
        .btn-text {
            font-size: 12px;
        }

        /* For Phone-side View */
        @media only screen and (min-width: 600px) {
            .btn-text {
                font-size: 16px;
            }
        }

        /* For Desktop Default View */
        @media only screen and (min-width: 1024px) {
            .btn-text {
                font-size: 20px;
            }
        }
    </style>

    <script>
        function hide_col_phone(table_col) {
            // To hide 规格 column
            hide_col_full('规格', table_col);

            // To hide 价格 column
            hide_col_full('价格', table_col);

            // To hide 数量 column
            hide_col_full('数量', table_col);

            // To hide 销售 column
            hide_col_full('销售', table_col);
        }

        function hide_col_ipad(table_col) {
            // To hide 数量 column
            hide_col_full('数量', table_col);

            // To hide 销售 column
            hide_col_full('销售', table_col);
        }

        $(function() {
            let table_col = get_table_col(1);
            generate_detail_html(table_col);

            if ($(window).width() <= 600) {
                //For phone
                hide_col_phone(table_col);
            } else if ($(window).width() > 600 && $(window).width() < 900) {
                //For Ipad default width and phone side-view
                hide_col_ipad(table_col);
            } else {
                //For Desktop
            }

            //check viewport (Idk how to do this in css)
            $(window).resize("on", e => {

                show_col_all(table_col);

                if ($(window).width() <= 600) {
                    //For phone
                    hide_col_phone(table_col);
                } else if ($(window).width() > 600 && $(window).width() < 900) {
                    //For Ipad default width and phone side-view
                    hide_col_ipad(table_col);
                } else {
                    //For Desktop
                }
            });

            //Show details
            let detail_flag = false;
            $("#detail_btn").on("click", e => {
                (detail_flag == true) ? $("#detail_board").css("display", "none"): $("#detail_board").css("display", "block");
                detail_flag = !detail_flag;
            });

            //Show or hide table columns
            $("#detail_board input:checkbox").change(function() {
                let tble = table_col[$(this).attr('name')],
                    ind = tble.ind;
                (tble.flag == true) ? hide_col(ind): show_col(ind);
                tble.flag = !tble.flag;
            });
        });
    </script>



    <main class="container">
        <div class="h1">商品管理</div>

        <form action="" method="post" id="action-form">
            <div class="row p-2">
                <input type="text" name="name" id="selectedName" value="" hidden />
                <div class="mr-2" style="width: 30%"><button onclick="addButtonClicked()" type="button" class="btn btn-primary btn-block btn-text" name="addButton" id="addButton">添加商品</button></div>
                <div class="mr-2" style="width: 30%"><button onclick="editButtonClicked()" type="button" class="btn btn-primary btn-block btn-text" name="editButton" id="editButton" disabled>编辑商品</button></div>
                <div style="width: 30%"><button onclick="deleteButtonClicked()" type="submit" class="btn btn-primary btn-block btn-text" name="deleteButton" id="deleteButton" disabled>删除商品</button></div>
            </div>
        </form>

        <!--Details-->
        <div class="d-flex justify-content-end mt-2">
            <div style="position: relative;">
                <button id="detail_btn" class="btn-sm btn-primary">&#9660; 详情</button>
                <div id="detail_board" style="height: 240px; width: 150px; background-color: white; display: none;position: absolute; z-index: 2; right: 0px;">
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <form action="{{ url('/item') }}" method="get">

                    <div class="form-row">
                        <div class="col-10">
                            <!-- Item searching -->
                            <input type="text" class="form-control" maxlength="20" name="search"
                                   value="{{ isset($_GET["search"]) ? $_GET["search"] : "" }}" />
                        </div>
                        <div class="col-2">
                            <input type="submit" class="btn btn-primary p-2 mt-0" value="搜索"/>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-6">
                {{ $items->links() }}
            </div>

        </div>

        <table class="table table-bordered mt-3" id="item-table">
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" onclick="selectAll(this)"></th>
                <th scope="col">名称</th>
                <th scope="col">规格</th>
                <th scope="col">货号</th>
                <th scope="col">价格</th>
                <th scope="col">数量</th>
                <th scope="col">销售</th>
                <th scope="col">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <input class="item-check-box" name="item-check-box" onclick="itemCheckBoxClicked(this)" type="checkbox">
                        <input type="text" name="name" class="infoBoxItemName" value="{{ $item->name }}" hidden/>
                        <input type="text" name="brand" class="infoBoxItemBrand" value="{{ $item->brand }}" hidden/>
                    </td>
                    <td>{{ $item->name }}</td>

                    <td>
                        @foreach($item->variations as $variation)
                            {{ $variation->name1 . ' ' . $variation->name2 }}<br>
                        @endforeach
                    </td>

                    <td>
                        @foreach($item->variations as $variation)
                            {{ $variation->barcode }}<br>
                        @endforeach
                    </td>

                    <td>
                        @foreach($item->variations as $variation)
                            @if($variation->discount != null)
                                RM{{ number_format($variation->price * $variation->discount->rate, 2, '.', '') }}<br>
                            @else
                                RM{{ number_format($variation->price, 2, '.', '') }}<br>
                            @endif

                        @endforeach
                    </td>

                    <td>
                        @foreach($item->variations as $variation)
                            {{ $variation->getTotalStock() }}<br>
                        @endforeach
                    </td>

                    <td>
                        0
                    </td>

                    <td>
                        <form action="" method="post">
                            <input type="text" name="name" value="{{ $item->name }}" hidden/>
                            <input type="text" name="brand" value="{{ $item->brand }}" hidden/>
                            <button class="btn btn-outline-secondary p-2 m-1" type="submit" name="list" style="width: 50px;">
                                {{ $item->util->is_listed ? "下架" : "上架" }}</button><br>
                            <button class="btn btn-outline-secondary p-2 m-1" type="submit" name='edit' style="width: 50px;">编辑</button><br>
                            <button class="btn btn-outline-secondary p-2 m-1" type="submit" name="delete" style="width: 50px;">删除</button><br>
                        </form>


                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>

    </main>

    <script>
        var selectedCount = 0;
        var selectedRowIndex = null;

        function itemCheckBoxClicked(source) {

            if (source.checked) {
                selectedCount++;
            } else {
                selectedCount--;
            }

            //Check to disable the edit button (Website cannot support multiple item editing)
            if (selectedCount != 1) {
                document.getElementById("editButton").setAttribute("disabled", "disabled");
                document.getElementById("deleteButton").setAttribute("disabled", "disabled");
            } else {
                document.getElementById("editButton").removeAttribute("disabled");
                document.getElementById("deleteButton").removeAttribute("disabled");
            }

            //set the row selected
            if (selectedCount == 1) {
                //Loop throught the class
                for (var i = 0; i < document.getElementsByClassName('item-check-box').length; i++) {
                    if (document.getElementsByClassName('item-check-box')[i].checked) {
                        selectedRowIndex = i + 1;
                    }
                }
                // Must use [0] to get first class selected
                document.getElementById("selectedName").value = source.parentNode.getElementsByClassName("infoBoxItemName")[0].value;
                document.getElementById("selectedBrand").value = source.parentNode.getElementsByClassName("infoBoxItemBrand")[0].value;
            } else {
                selectedRowIndex = null;
                document.getElementById("selectedName").value = "";
                document.getElementById("selectedBrand").value = "";
            }

        }

        function selectAll(source) {
            checkboxes = document.getElementsByName('item-check-box');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }

            if (source.checked) {
                selectedCount = document.getElementsByClassName('item-check-box').length;
                document.getElementById("editButton").setAttribute("disabled", "disabled");
                document.getElementById("deleteButton").setAttribute("disabled", "disabled");
            } else {
                selectedCount = 0;
                document.getElementById("editButton").setAttribute("disabled", "disabled");
                document.getElementById("deleteButton").setAttribute("disabled", "disabled");
                document.getElementById("selectedName").value = "";
                document.getElementById("selectedBrand").value = "";
            }
        }


        var myTable = document.getElementById("item-table");

        function untickAll() {
            //Untick all selection
            for (var i = 0; i < document.getElementsByClassName('item-check-box').length; i++) {
                if (document.getElementsByClassName('item-check-box')[i].checked) {
                    document.getElementsByClassName('item-check-box')[i].checked = false;
                }
            }
        }

        function addButtonClicked() {
            untickAll();
            document.location.href = "item-create.php";
        }

        function editButtonClicked() {
            untickAll();

            document.location.href = "item-edit.php?" +
                "itemName=" + document.getElementById("selectedName").value;
        }

        function deleteButtonClicked() {
            untickAll();
        }

    </script>
@endsection
