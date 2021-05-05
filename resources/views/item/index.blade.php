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


        <div class="">

            <div class="row">
                <div class="col">
                    <div style="font-size: 40px; font-weight: bolder">商品管理</div>
                </div>
                <div class="col">
                    <div class="row mb-2">
                        <div class="col-12 text-right">
                            <a href="{{ url('/item/create') }}">
                                <i class="icofont icofont-ui-add"></i> 添加商品
                            </a>
                        </div>
                    </div>
                    <div class="row text-right">
                        <div class="col-8">
                            <input type="text"
                                   class="form-control form-control-sm m-0"
                                   maxlength="20"
                                   placeholder="搜索名称、货号、规格、出产地、品牌、商品描述"
                                   value="{{ isset($_GET["search"]) ? $_GET["search"] : "" }}"
                                   disabled>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-sm m-0 w-100" disabled>
                                <i class="icofont icofont-search"></i> 搜索
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <table class="table table-bordered mt-3" id="item-table">
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
                        <a href="#">{{ $item->name }}</a>
                    </td>
                    <td>
                        @foreach($item->variations as $variation)
                            {{ $variation->name1 . ' ' . $variation->name2 }}<br>
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
                        </button><br>

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
        function confirmDelete(source){
            let button = jQuery(source);
            let form = button.parent().find('.delete-item-form');

            if(confirm('您确定要删除 ' + button.val() + ' 吗？')){
                form.submit();
            }
        }
    </script>
@endsection
