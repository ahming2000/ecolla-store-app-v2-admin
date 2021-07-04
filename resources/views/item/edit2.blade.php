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

    .btn-circle-40 {
        width: 40px;
        height: 40px;
        padding: 0px 0px 0px 0px;
        border-radius: 50px;
        font-size: 12px;
        text-align: center;
    }
    .btn-circle-60 {
        width: 60px;
        height: 60px;
        padding: 0px 0px 0px 0px;
        border-radius: 50px;
        font-size: 18px;
        text-align: center;
    }
    .border-2 {
        border-width: 2px !important;
    }
    .border-4 {
        border-width: 4px !important;
    }

    .label {
        font-size: 14px;
        color: grey;
        margin: 0px;
        padding: 0px;
    }

    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 15px;
        width: 15px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(18px);
        -ms-transform: translateX(18px);
        transform: translateX(18px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 35px;
    }

    .slider.round:before {
        border-radius: 50%;
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