@extends('layouts.app')

@section('title')
    编辑 {{ $item->name }}
@endsection

@section('extraStyle')

@endsection('extraStyle')

@section('content')
<main class="container">
    <edit-item :item='{{ $item }}'></edit-item>
</main>
@endsection