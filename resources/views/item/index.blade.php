@inject('helper', \App\Util\ViewHelper::class)
@inject('CategoryTable', \App\Models\Category::class)
@inject('ItemTable', \App\Models\Item::class)

@extends('layouts.app')

@section('title')
    商品管理
@endsection

@section('content')
    <main class="container">

        @if(session()->has('message'))
            <div class="alert alert-info text-center" role="alert">
                {!! session('message') !!}
            </div>
        @endif

        <div class="d-flex justify-content-between mb-1">
            <div class="h1">商品管理</div>

            <div>
                @if(auth()->user()->hasAccess('item_create'))
                    <button type="button" class="btn btn-outline-primary"
                            onclick="window.location.href = '{{ url('/item/create') }}'">
                        <i class="icofont icofont-ui-add"></i> 添加商品
                    </button>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 col-sm-12"></div>

            <div class="col-md-6 col-sm-12">

                <select name="paginate" id="paginateSelector" class="form-select shadow mb-2">
                    <option value="25" {{ $helper->paramSelected('paginate', '25') }}>一页显示25件商品</option>
                    <option value="50" {{ $helper->paramSelected('paginate', '50') }}>一页显示50件商品</option>
                    <option value="75" {{ $helper->paramSelected('paginate', '75') }}>一页显示75件商品</option>
                    <option value="100" {{ $helper->paramSelected('paginate', '100') }}>一页显示100件商品</option>
                </select>

                <form action="{{ url('/item') }}" method="get">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="flex-grow-1 me-2">
                            <input type="hidden" name="paginate" value="{{ $helper->param('paginate', 25) }}">
                            <input type="hidden" name="category" value="{{ $helper->param('category') }}">

                            <input type="text" class="form-control shadow" maxlength="20" name="search"
                                   placeholder="搜索名称、货号、规格、出产地、商品描述" value="{{ $helper->param('search') }}">
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="icofont icofont-search"></i> 搜索
                            </button>
                        </div>
                    </div>
                </form>

                <select class="form-select shadow mb-2" name="category" id="categorySelector">
                    <option value="">
                        全部商品 ({{ $ItemTable->all()->count() }})
                    </option>

                    @foreach($categories as $category)
                        @if($CategoryTable->getItemCount($category->id) != 0)
                            <option
                                value="{{ $category->name }}" {{ $helper->paramSelected('category', $category->name) }}>
                                {{ $category->name }} ({{ $CategoryTable->getItemCount($category->id) }})
                            </option>
                        @endif
                    @endforeach
                </select>

                <select class="form-select shadow mb-2" name="specialFilter" id="specialFilterSelector" hidden>
                    <option value="">选择进阶分类</option>
                    <option value="mostSales" {{ $helper->paramSelected('special', 'mostSales') }}>销量最高</option>
                    <option value="mostViews" {{ $helper->paramSelected('special', 'mostViews') }}>最多点击</option>
                    <option value="notListed" {{ $helper->paramSelected('special', 'notListed') }}>已下架</option>
                    <option value="noStock" {{ $helper->paramSelected('special', 'noStock') }}>无库存</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">

            @foreach($items as $item)
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <div class="card">

                        <a href="{{ url('/item/' . $item->id) }}" class="no-anchor-style">
                            <img src="{{ $item->getCoverImage() }}" class="card-img-top" alt="" loading="lazy">
                        </a>

                        <div class="card-body">

                            @if(auth()->user()->hasAccess('item_list'))
                                <listing-switch item-id="{{ $item->id }}"
                                                is-listed="{{ $item->util->is_listed }}"></listing-switch>
                            @endif

                            <a href="{{ url('/item/' . $item->id) }}" class="no-anchor-style">
                                <h6 class="text-truncate">{{ $item->name }}</h6>
                            </a>

                            <div class="mb-2">
                                规格：<br>
                                @foreach($item->variations as $variation)
                                    <div class="d-flex justify-content-between">
                                        <div class="text-truncate">{{ $variation->name }}</div>
                                        <div>RM{{ $variation->getPrice() }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6 mb-1">
                                    @if(auth()->user()->hasAccess('item_update'))
                                        <button type="button" class=" btn btn-primary btn-sm w-100"
                                                onclick="window.location.href = '{{ url('/item/' . $item->id . '/edit') }}'">
                                            <i class="icofont icofont-ui-edit"></i> 编辑
                                        </button>
                                    @endif
                                </div>

                                <div class="col-12 col-sm-6 mb-1">
                                    @if(auth()->user()->hasAccess('item_delete'))
                                        <button type="button" class="btn btn-danger btn-sm w-100"
                                                onclick="confirmDelete(this)"
                                                value="{{ $item->name }}">
                                            <i class="icofont icofont-ui-delete"></i> 删除
                                        </button>

                                        <form action="{{ url('/item/' . $item->id) }}" method="post"
                                              class="delete-item-form">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
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

@section('script')
    <script>
        @if(auth()->user()->hasAccess('item_delete'))
            function confirmDelete(source) {
                let button = jQuery(source);
                let form = button.parent().find('.delete-item-form');
                if (confirm('您确定要删除 ' + button.val() + ' 吗？')) {
                    form.submit();
                }
            }
        @endif

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
