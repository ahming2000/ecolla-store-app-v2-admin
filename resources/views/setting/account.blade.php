@extends('layouts.app')

@section('title')
    个人账号设定
@endsection

@section('content')
    <main class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-6 offset-md-2 offset-lg-3">

                @if(session()->has('message'))
                    <div class="alert alert-info text-center" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="h1">个人账号设定</div>

                <table class="table table-borderless">
                    <tr>
                        <td>名字：</td>
                        <td>
                            {{ auth()->user()->name }}
                            <a href="#"
                               style="color: blue"
                               data-toggle="modal"
                               data-target="#editNameModal">
                                点击进行修改
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>邮箱：</td>
                        <td>{{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <td>密码：</td>
                        <td>
                            <a href="#" style="color: blue"
                               data-toggle="modal"
                               data-target="#editPasswordModal">
                                点击进行修改
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="editNameModal" tabindex="-1" role="dialog" aria-labelledby="editNameModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNameModalLabel">更换名字</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ url('/setting/account/name') }}">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="新名字">
                        @error("name")
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger btn-md shadow-none" data-dismiss="modal">
                            取消
                        </button>
                        <button type="submit" class="btn btn-primary btn-md">
                            修改
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPasswordModal" tabindex="-1" role="dialog" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPasswordModalLabel">更换密码</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ url('/setting/account/password') }}">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <div class="col-12 mb-2">
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" placeholder="旧密码">
                            @error("old_password")
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="密码">
                            @error("password")
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="密码确认">
                            @error("password_confirmation")
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger btn-md shadow-none" data-dismiss="modal">
                            取消
                        </button>
                        <button type="submit" class="btn btn-primary btn-md">
                            更改
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('extraScriptEnd')
    <script>
        // Auto toggle the selected modal with needed
        $(document).ready(function () {
            @if(isset($_GET['show']))
                $('#edit{{ $_GET['show'] }}Modal').modal('toggle');
            @endif
        });
    </script>
@endsection
