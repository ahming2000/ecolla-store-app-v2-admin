@extends('layouts.app')

@section('title')
    商品 {{ $item->name }}
@endsection

@section('content')
    <main class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12 offset-md-2">
                <div class="h1">商品详情</div>
                <textarea id="desc" hidden>{{ $item->desc }}</textarea>
                <table class="table">
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
                        <td>描述</td>
                        <td id="desc-display"></td>
                    </tr>
                    <tr>
                        <td>规格</td>
                        <td>
                            @foreach($item->variations as $v)
                                -{{ $v->name . ' (RM' . number_format($v->price, 2, '.', '') . ')' }}<br>
                            @endforeach
                        </td>
                    </tr>

                    </tbody>
                </table>

                <div class="row d-flex justify-content-center">
                    @if(auth()->user()->hasAccess('item_update'))
                        <a href="{{ url('/item/' . $item->id . '/edit') }}" class="btn btn-primary btn-sm">
                            <i class="icofont icofont-ui-edit"></i> 编辑商品
                        </a>
                    @endif
                    @if(auth()->user()->hasAccess('item_delete'))
                        <button type="button"
                                class="btn btn-primary btn-sm"
                                onclick="confirmDelete(this)"
                                value="{{ $item->name }}">
                            <i class="icofont icofont-ui-delete"></i> 删除商品
                        </button>

                        <form action="{{ url('/item/' . $item->id) }}" method="post" class="delete-item-form">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection

@section('extraScriptEnd')
    <script>
        // Convert textarea format to paragraph
        document.getElementById('desc-display').innerHTML = document.getElementById('desc').value.split('\n').join('<br>').split(' ').join('&nbsp;');
    </script>

    <script>
        @if(auth()->user()->hasAccess('item_delete'))
            function confirmDelete(source){
                let button = jQuery(source);
                let form = button.parent().find('.delete-item-form');

                if(confirm('您确定要删除 ' + button.val() + ' 吗？')){
                    form.submit();
                }
            }
        @endif
    </script>
@endsection
