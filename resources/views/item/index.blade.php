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

        <div class="row mb-3">
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
                <div class="row text-right mb-2">
                    <div class="col-12">
                        <select name="paginate" id="paginateSelector" class="custom-select custom-select-sm">
                            <option value="30" {{ @$_GET['paginate'] == 25 ? "selected" : "" }}>一页显示25件商品</option>
                            <option value="50" {{ @$_GET['paginate'] == 50 ? "selected" : "" }}>一页显示50件商品</option>
                            <option value="80" {{ @$_GET['paginate'] == 75 ? "selected" : "" }}>一页显示75件商品</option>
                            <option value="100" {{ @$_GET['paginate'] == 100 ? "selected" : "" }}>一页显示100件商品</option>
                        </select>
                    </div>
                </div>
                <form action="{{ url('/item') }}" method="get">
                    <div class="row text-right mb-2">
                        <div class="col-8">
                            @if(isset($_GET['paginate']))
                                <input type="hidden" name="paginate" value="{{ $_GET['paginate'] }}">
                            @endif
                            @if(isset($_GET['category']))
                                <input type="hidden" name="category" value="{{ $_GET['category'] }}">
                            @endif
                            <input type="text"
                                   class="form-control form-control-sm m-0"
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
                <div class="row text-right mb-2">
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
            </div>
        </div>

        <div class="row mb-3">
            @foreach($items as $item)
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
                                    <listing-switch item-id="{{ $item->id }}"
                                                    is-listed="{{ $item->util->is_listed }}"></listing-switch>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <h6 class="text-truncate">
                                        <a href="{{ url('/item/' . $item->id) }}">{{ $item->name }}</a>
                                    </h6>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">规格：</div>
                                <div class="col-12">
                                    @foreach($item->variations as $variation)
                                        <div class="row">
                                            <div class="col-6">
                                                {{ $variation->name }}
                                            </div>
                                            <div class="col-6 text-right">
                                                @if($variation->discount != null)
                                                    RM{{ number_format($variation->price * $variation->discount->rate, 2, '.', '') }}
                                                @else
                                                    RM{{ number_format($variation->price, 2, '.', '') }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <button type="button" class=" btn btn-secondary btn-sm w-100 ml-0"
                                            onclick="window.location.href = '{{ url('/item/' . $item->id . '/edit') }}'">
                                        <i class="icofont icofont-ui-edit"></i> 编辑
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary btn-sm w-100 ml-0" onclick="confirmDelete(this)"
                                            value="{{ $item->name }}">
                                        <i class="icofont icofont-ui-delete"></i> 删除
                                    </button>

                                    <form action="{{ url('/item/' . $item->id) }}" method="post"
                                          class="delete-item-form">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="row d-flex justify-content-center">
            {{ $items->links() }}
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

        $(document).ready(function () {
            $('#paginateSelector').on('change', function () {
                window.location.href = "/item{!! $params['paginate'] !!}paginate=" + $('#paginateSelector option:selected').val();
            });
            $("#categorySelector").on("change", function () {
                window.location.href = "/item{!! $params['category'] !!}category=" + $('#categorySelector option:selected').val();
            });
        });
    </script>
@endsection
