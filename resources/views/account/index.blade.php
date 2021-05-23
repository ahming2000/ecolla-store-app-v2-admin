@extends('layouts.app')

@section('title')
员工账户管理
@endsection

@section('extraStyle')
<style type="text/css">
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

@section('extraScript')
<script type="text/javascript">
    function showAlert() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.alert').fadeIn();
        setTimeout(function() {
            hideAlert();
        }, 3000);
    }

    function hideAlert() {
        $('.alert').fadeOut();
    }

    var closeAlertHtml = '<a href="#" class="close" onclick="hideAlert()">&times;</a>';

    function showAddAccountSuccessAlert() {
        $('.alert').html("新员工 Kakashi 添加成功！" + closeAlertHtml);
        showAlert();
    }

    function showEditAccountSuccessAlert() {
        $('.alert').html('员工 Sasuke 已被更新。' + closeAlertHtml);
        showAlert();
    }

    function showDeleteAccountSuccessAlert() {
        $('.alert').html('员工 Sakura 已被删除。' + closeAlertHtml);
        showAlert();
    }
</script>
@endsection('extraScript')

@section('content')

<?php

class Permission
{
    public $cnDisplayName;
    public $elementId;
    public $checkedByDefault;

    function __construct($cnDisplayName, $elementId, $checkedByDefault)
    {
        $this->cnDisplayName = $cnDisplayName;
        $this->elementId = $elementId;
        $this->checkedByDefault = $checkedByDefault;
    }
}

$permissions = array(
    new Permission('商品查看', 'ItemView', true),
    new Permission('商品创建', 'ItemCreate', false),
    new Permission('商品编辑', 'ItemUpdate', false),
    new Permission('商品删除', 'ItemDelete', false),
    new Permission('商品上架', 'ItemListing', false),

    new Permission('订单查看', 'OrderView', true),
    new Permission('订单基本属性编辑', 'OrderUpdate', false),
    new Permission('订单删除', 'OrderDelete', false),
    new Permission('顾客收据查看', 'OrderReceiptView', true),
    new Permission('订单详情下载', 'OrderInvoiceDownload', false),

    new Permission('订单商品查看', 'OrderItemView', true),
    new Permission('订单商品创建', 'OrderItemCreate', false),
    new Permission('订单商品编辑', 'OrderItemUpdate', false),
    new Permission('订单商品删除', 'OrderItemDelete', false),

    new Permission('仪表板查看', 'DashboardView', true),
    new Permission('设定查看', 'SettingsView', true),
    new Permission('账号设置', 'AccountSettings', true),
    new Permission('商品相关设定', 'ItemPropertiesSettings', false),
    new Permission('订单相关设定', 'OrderPropertiesSettings', false),
    new Permission('显示数量相关设定', 'PaginationPropertiesSettings', false),
);
?>

<main class="container">
    <div class="h1">员工账户管理</div>

    <!-- Alert Message -->
    <div class="alert alert-success alert-dismissable" role="alert" style="display: none;">
    </div>

    <div class="row-flex d-flex justify-content-end w-100 my-2">
        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addAccountModal">
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
                            <div class="row d-flex align-items-center">
                                <h4 class="card-title m-0">{{ $user->name }}</h4>
                                @if($user->status == 'disable')
                                <span class="badge rounded-pill bg-warning shadow-none text-dark ml-2 px-2 h-75">未激活</span>
                                @endif
                            </div>
                            <div class="row">
                                <p class="card-subtitle m-0">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-column d-flex align-items-center justify-content-center my-2">
                        <div class="row">
                            <div class="col-4 d-flex">
                                <button type="submit" class="btn btn-secondary btn-md d-flex justify-content-center align-items-center text-nowrap" type="button" data-toggle="modal" data-target="#editAccountModal" @if($user->status == 'disable')
                                    disabled
                                    @endif
                                    >
                                    <p class=" text-center m-0">编辑</p>
                                </button>
                            </div>
                            <div class="col-4 d-flex">
                                @if($user->status == 'enable')
                                <button type="submit" class="btn btn-warning btn-md d-flex justify-content-center align-items-center text-nowrap">
                                    <p class="text-center m-0">停用</p>
                                </button>
                                @elseif($user->status == 'disable')
                                <button type="submit" class="btn btn-success btn-md d-flex justify-content-center align-items-center text-nowrap">
                                    <p class="text-center m-0">激活</p>
                                </button>
                                @endif
                            </div>
                            <div class="col-4 d-flex">
                                <button type="submit" class="btn btn-danger btn-md d-flex justify-content-center align-items-center text-nowrap" type="button" data-toggle="modal" data-target="#deleteAccountModal">
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

    <!-- Add Account Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">添加员工账户</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group md-form">
                            <input type="email" class="form-control" id="addAccountNewEmailControl">
                            <label for="addAccountNewEmailControl">新员工邮箱</label>
                        </div>
                        <div class="form-group md-form">
                            <input type="text" class="form-control" id="addAccountNewFullNameControl">
                            <label for="addAccountNewFullNameControl">新员工姓名</label>
                        </div>
                        <div class="form-group md-form">
                            <input type="password" class="form-control" id="addAccountNewPasswordControl">
                            <label for="addAccountNewPasswordControl">新员工密码</label>
                        </div>
                        <div class="form-group md-form">
                            <input type="password" class="form-control" id="addAccountConfirmPasswordControl">
                            <label for="addAccountConfirmPasswordControl">新员工密码（重填确认）</label>
                        </div>
                        <div class="form-group">
                            <label>权限</label>
                            <ul class="list-group">
                                @foreach($permissions as $permission)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-8 d-flex align-items-center">
                                            <p class="m-0">{{ $permission->cnDisplayName }}</p>
                                        </div>
                                        <div class="col-4 d-flex align-items-center justify-content-end">
                                            <label class="switch m-0">
                                                <input type="checkbox" class="form-control" id="addAccount{{ $permission->elementId }}Permission" @if($permission->checkedByDefault)
                                                checked
                                                @endif
                                                >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-md shadow-none" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary btn-md" data-dismiss="modal" onclick="showAddAccountSuccessAlert()">添加</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccountModalLabel">编辑员工账户</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group md-form">
                            <input type="email" class="form-control" id="editAccountNewEmailControl">
                            <label for="addAccountNewEmailControl">员工邮箱</label>
                        </div>
                        <div class="form-group md-form">
                            <input type="text" class="form-control" id="editAccountNewFullNameControl">
                            <label for="addAccountNewFullNameControl">员工姓名</label>
                        </div>
                        <div class="form-group md-form">
                            <input type="password" class="form-control" id="editAccountNewPasswordControl">
                            <label for="addAccountNewPasswordControl">员工密码</label>
                        </div>
                        <div class="form-group md-form">
                            <input type="password" class="form-control" id="editAccountConfirmPasswordControl">
                            <label for="addAccountConfirmPasswordControl">员工密码（重填确认）</label>
                        </div>

                        <div class="form-group">
                            <label>权限</label>
                            <ul class="list-group">
                                @foreach($permissions as $permission)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-8 d-flex align-items-center">
                                            <p class="m-0">{{ $permission->cnDisplayName }}</p>
                                        </div>
                                        <div class="col-4 d-flex align-items-center justify-content-end">
                                            <label class="switch m-0">
                                                <input type="checkbox" class="form-control" id="addAccount{{ $permission->elementId }}Permission" @if($permission->checkedByDefault)
                                                checked
                                                @endif
                                                >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-md shadow-none" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary btn-md" data-dismiss="modal" onclick="showEditAccountSuccessAlert()">更新</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">删除员工账户</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col">
                        <p>确定删除此员工账户？此动作无法挽回。</p>
                        <div class="col flex-column d-inline-flex justify-content-center">
                            <p class="m-0 p-0 h5 d-inline-flex">Sakura</p>
                            <p class="m-0 p-0 text-muted d-inline-flex">sakura@newrainbowmarket.com</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary btn-md shadow-none" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal" onclick="showDeleteAccountSuccessAlert()">删除</button>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection