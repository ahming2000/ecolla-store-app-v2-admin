@extends('layouts.app')

@section('title')
    个人账号设定
@endsection

@section('content')
    <main class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-6 offset-md-2 offset-lg-3">
                <div class="h1">个人账号设定</div>

                <table class="table table-borderless">
                    <tr>
                        <td>名字：</td>
                        <td>{{ auth()->user()->name }} <a href="#" style="color: blue">点击进行修改</a></td>
                    </tr>
                    <tr>
                        <td>邮箱：</td>
                        <td>{{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <td>密码：</td>
                        <td><a href="#" style="color: blue">点击进行修改</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </main>


@endsection
