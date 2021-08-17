@extends('layouts.app')

@section('title')
    {{ $item->name }}
@endsection

@section('content')
    <main class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12 offset-md-2">

                <textarea id="desc" hidden>{{ $item->desc }}</textarea>

                <div class="d-flex justify-content-between mb-2">
                    <div class="h1">
                        商品详情
                    </div>

                    <div class="my-auto">
                        @if(auth()->user()->hasAccess('item_update'))
                            <a href="{{ url('/item/' . $item->id . '/edit') }}" class="btn btn-primary">
                                <i class="icofont icofont-ui-edit"></i> 编辑
                            </a>
                        @endif

                        @if(auth()->user()->hasAccess('item_delete'))
                            <button type="button"
                                    class="btn btn-danger"
                                    onclick="confirmDelete(this)"
                                    value="{{ $item->name }}">
                                <i class="icofont icofont-ui-delete"></i> 删除
                            </button>

                            <form action="{{ url('/item/' . $item->id) }}" method="post" class="delete-item-form">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>
                </div>

                <table class="table table-light">
                    <tbody>
                    <tr>
                        <td>名称</td>
                        <td>{{ $item->name }}</td>
                    </tr>
                    <tr>
                        <td>出产地</td>
                        <td>{{ $item->origin }}</td>
                    </tr>
                    <tr>
                        <td>分类/标签</td>
                        <td>
                            @foreach($item->categories as $category)
                                <span class="badge bg-primary me-1">{{ $category->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" id="desc-display"></td>
                    </tr>
                    </tbody>
                </table>

                <div class="h1">
                    规格
                </div>

                <table class="table table-light">
                    <thead>
                    <tr>
                        <th scope="col">名称</th>
                        <th scope="col">价钱</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($item->variations as $v)
                        <tr>
                            <td>{{ $v->name }}</td>
                            <td>{{ 'RM' . number_format($v->price, 2, '.', '') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // Convert textarea format to paragraph
        document.getElementById('desc-display').innerHTML = "描述：<br>" + document.getElementById('desc').value.split('\n').join('<br>').split(' ').join('&nbsp;');
    </script>

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
    </script>
@endsection
