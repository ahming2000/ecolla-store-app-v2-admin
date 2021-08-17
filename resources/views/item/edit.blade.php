@extends('layouts.app')

@section('title')
    编辑 {{ $item->name }}
@endsection

@section('content')
    <main class="container">
        <div class="h1">商品属性编辑</div>
        <div>
            <edit-item :item='{{ $item }}' :all-categories="{{ json_encode($categories, true) }}"></edit-item>
        </div>
    </main>
@endsection
