@extends('layouts.app')

@section('title')
    设定主页
@endsection

@section('content')
    <main class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 offset-md-1 offset-lg-2">
                <div class="h1">设定主页</div>
                <div class="row d-flex justify-content-between mt-5">
                    <div class="col-6 text-center">
                        <a href="{{ url('/setting/website') }}" class="btn btn-primary btn-block">网站属性设定</a>
                    </div>
                    <div class="col-6 text-center">
                        <a href="{{ url('/setting/account') }}" class="btn btn-primary btn-block">个人账号设定</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
