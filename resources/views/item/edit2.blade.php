@extends('layouts.app')

@section('title')
编辑 {{ $item->name }}
@endsection

@section('extraStyle')
<style>
    .scroll {
        white-space: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .scroll li {
        display: inline-block;
    }

    .btn-circle {
        width: 60px;
        height: 60px;
        padding: 0px 0px 0px 0px;
        border-radius: 50px;
        font-size: 18px;
        text-align: center;
    }
</style>
@endsection('extraStyle')

@section('content')
<main class="container">
    <div class="h1">商品属性编辑</div>
    <div>
        <edit-item :item='{{ $item }}' :all-categories="{{ json_encode($categories, true) }}"></edit-item>
    </div>
</main>
@endsection