@extends('layouts.app')

@section('title')
员工账户管理
@endsection

@section('content')
<style type="text/css">

</style>

<main class="container">
    <div class="h1">员工账户管理</div>

    <div class="row-flex d-flex justify-content-end w-100 my-2">
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            <i class="icofont-ui-add icofont-1x mr-2"></i>添加员工账户
        </button>
    </div>

    <div>
        <ul class="list-group">
            @foreach($users as $user)
            <li class="card my-2">
                <div class="card-body d-flex justify-content-center flex-wrap">

                    <div class="col-md-8 d-flex align-items-center my-2">
                        <div class="flex-column">
                            <div class="row">
                                <h4 class="card-title m-0">{{ $user->name }}</h4>
                                
                            </div>

                            <p class="card-subtitle m-0">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex-column d-flex align-items-center justify-content-center my-2">
                        <div class="row">
                            <div class="col-4 d-flex">
                                <button type="submit" class="btn btn-secondary btn-md d-flex justify-content-center align-items-center text-nowrap">
                                    <p class="text-center m-0">编辑</p>
                                </button>
                            </div>
                            <div class="col-4 d-flex">
                                <button type="submit" class="btn btn-warning btn-md d-flex justify-content-center align-items-center text-nowrap">
                                    <p class="text-center m-0">停用</p>
                                </button>
                            </div>
                            <div class="col-4 d-flex">
                                <button type="submit" class="btn btn-danger btn-md d-flex justify-content-center align-items-center text-nowrap">
                                    <p class="text-center m-0">删除</p>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </li>
            @endforeach
        </ul>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection