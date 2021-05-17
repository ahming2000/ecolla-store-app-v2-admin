@extends('layouts.app')

@section('title')
    商品管理
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
                <div style="font-size: 40px; font-weight: bolder">商品管理</div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="row mb-2">
                    <div class="col-12 text-right">
                        <a href="{{ url('/item/create') }}">
                            <i class="icofont icofont-ui-add"></i> 添加商品
                        </a>
                    </div>
                </div>
                <form action="{{ url('/item') }}" method="get">
                    <div class="row text-right mb-2">
                        <div class="col-8">
                            <input type="text"
                                   class="form-control form-control-sm m-0 @error("search") is-invalid @enderror"
                                   maxlength="20"
                                   name="search"
                                   placeholder="搜索名称、货号、规格、出产地、商品描述"
                                   value="{{ $_GET["search"] ?? "" }}">

                            @error("search")
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-sm m-0 w-100">
                                <i class="icofont icofont-search"></i> 搜索
                            </button>
                        </div>
                    </div>
                </form>
                <form action="{{ url('/item') }}" method="get">
                    <div class="row text-right">
                        <div class="col-12">
                            <select class="custom-select custom-select-sm" name="category" id="categorySelector">
                                <option value="">
                                    全部商品 (<?= \App\Models\Item::all()->count() ?>)
                                </option>

                                @foreach($categories as $category)
                                    @if(\App\Models\Category::getItemCount($category->id) != 0)
                                        <option value="{{ $category->name }}"
                                            {{ @$_GET['category'] == $category->name || @$_GET['category'] == $category->name_en ? " selected" : "" }}>
                                            {{ $category->name }}
                                            ({{ \App\Models\Category::getItemCount($category->id) }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th scope="col">名称</th>
                <th scope="col">规格</th>
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
                        <a href="{{ url('/item/' . $item->id) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        @foreach($item->variations as $variation)
                            {{ $variation->name }}<br>
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
                            {{ $variation->stock }}<br>
                        @endforeach
                    </td>
                    <td>
                        {{ $item->util->sold ?? 0 }}
                    </td>
                    <td>
                        <a href="#" class="btn btn-secondary btn-sm mb-1">
                            <i class="icofont icofont-basket"></i> {{ $item->util->is_listed == '1' ? "下架" : "上架" }}
                        </a><br>
                        <a href="{{ url('/item/' . $item->id . '/edit') }}" class="btn btn-secondary btn-sm mb-1">
                            <i class="icofont icofont-ui-edit"></i> 编辑
                        </a><br>
                        <button type="button"
                                class="btn btn-secondary btn-sm m-0 mb-1"
                                onclick="confirmDelete(this)"
                                value="{{ $item->name }}">
                            <i class="icofont icofont-ui-delete"></i> 删除
                        </button>
                        <br>

                        <form action="{{ url('/item/' . $item->id) }}" method="post" class="delete-item-form">
                            @csrf
                            @method('DELETE')
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        </div>
    </main>
@endsection

@section('extraScriptEnd')
    <script>
        function confirmDelete(source) {
            let button = jQuery(source);
            let form = button.parent().find('.delete-item-form');

            if (confirm('您确定要删除 ' + button.val() + ' 吗？')) {
                form.submit();
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            // Category bar onchange bar
            $("#categorySelector").on("change", function () {
                if ($("#categorySelector option:selected").val() !== "") {
                    window.location.href = "/item?<?= isset($_GET["search"]) ? "search=" . $_GET["search"] . "&" : ""; ?>category=" + $("#categorySelector option:selected").val();
                } else {
                    window.location.href = "/item<?= isset($_GET["search"]) ? "?search=" . $_GET["search"] : ""; ?>";
                }
            });
        });
    </script>
@endsection
