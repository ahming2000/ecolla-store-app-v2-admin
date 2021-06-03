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
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
        $('.alert').slideDown(500);
        setTimeout(function() {
            hideAlert();
            setTimeout(function() {
                location.reload();
            }, 2000);
        }, 3000);
    }

    function hideAlert() {
        $('.alert').slideUp(1000);
    }

    var closeAlertHtml = '<a href="#" class="close" onclick="hideAlert()">&times;</a>';

    function showAddAccountSuccessAlert() {
        var fullName = $('#addAccountModal #addAccountNewFullNameControl').val();

        $('.alert').html('新员工 ' + fullName + ' 添加成功！' + closeAlertHtml);
        showAlert();
    }

    function showEditAccountSuccessAlert() {
        var fullName = $('#editAccountModal #editAccountFullNameControl').val();

        $('.alert').html('员工 ' + fullName + ' 已被更新。' + closeAlertHtml);
        showAlert();
    }

    function showDeleteAccountSuccessAlert() {
        var fullName = $('#deleteAccountModal #deleteAccountFullNameDisplay').html();

        $('.alert').html('员工 ' + fullName + ' 已被删除。' + closeAlertHtml);
        showAlert();
    }

    function readUserDataToEdit(element) {
        var email = $(element).data('user-email');
        var fullName = $(element).data('user-name');

        var userPermisionItemView = $(element).data('user-permission-item-view');
        var userPermisionItemCreate = $(element).data('user-permission-item-create');
        var userPermisionItemUpdate = $(element).data('user-permission-item-update');
        var userPermisionItemDelete = $(element).data('user-permission-item-delete');
        var userPermisionItemListing = $(element).data('user-permission-item-listing');

        var userPermisionOrderView = $(element).data('user-permission-order-view');
        var userPermisionOrderUpdate = $(element).data('user-permission-order-update');
        var userPermisionOrderDelete = $(element).data('user-permission-order-delete');
        var userPermisionOrderReceiptView = $(element).data('user-permission-order-receipt-view');
        var userPermisionOrderInvoiceDownload = $(element).data('user-permission-order-invoice-download');

        var userPermisionOrderItemView = $(element).data('user-permission-order-item-view');
        var userPermisionOrderItemCreate = $(element).data('user-permission-order-item-create');
        var userPermisionOrderItemUpdate = $(element).data('user-permission-order-item-update');
        var userPermisionOrderItemDelete = $(element).data('user-permission-order-item-delete');

        var userPermisionDashboardView = $(element).data('user-permission-dashboard-view');
        var userPermisionSettingsView = $(element).data('user-permission-settings-view');
        var userPermisionAccountSettingsDelete = $(element).data('user-permission-account-settings');
        var userPermisionItemPropertiesSettings = $(element).data('user-permission-item-properties-settings');
        var userPermisionOrderPropertiesSettings = $(element).data('user-permission-order-properties-settings');
        var userPermisionPaginationPropertiesSettings = $(element).data('user-permission-pagination-properties-settings');

        $('#editAccountModal #editAccountEmailControl').val(email);
        $('#editAccountModal #editAccountEmailControl').siblings('label').addClass('active');
        $('#editAccountModal #editAccountFullNameControl').val(fullName);
        $('#editAccountModal #editAccountFullNameControl').siblings('label').addClass('active');

        $('#editAccountModal #editAccountItemViewPermissionControl').prop('checked', !!userPermisionItemView);
        $('#editAccountModal #editAccountItemCreatePermissionControl').prop('checked', !!userPermisionItemCreate);
        $('#editAccountModal #editAccountItemUpdatePermissionControl').prop('checked', !!userPermisionItemUpdate);
        $('#editAccountModal #editAccountItemDeletePermissionControl').prop('checked', !!userPermisionItemDelete);
        $('#editAccountModal #editAccountItemListingPermissionControl').prop('checked', !!userPermisionItemListing);

        $('#editAccountModal #editAccountOrderViewPermissionControl').prop('checked', !!userPermisionOrderView);
        $('#editAccountModal #editAccountOrderUpdatePermissionControl').prop('checked', !!userPermisionOrderUpdate);
        $('#editAccountModal #editAccountOrderDeletePermissionControl').prop('checked', !!userPermisionOrderDelete);
        $('#editAccountModal #editAccountOrderReceiptViewPermissionControl').prop('checked', !!userPermisionOrderReceiptView);
        $('#editAccountModal #editAccountOrderInvoiceDownloadPermissionControl').prop('checked', !!userPermisionOrderInvoiceDownload);

        $('#editAccountModal #editAccountOrderItemViewPermissionControl').prop('checked', !!userPermisionOrderItemView);
        $('#editAccountModal #editAccountOrderItemCreatePermissionControl').prop('checked', !!userPermisionOrderItemCreate);
        $('#editAccountModal #editAccountOrderItemUpdatePermissionControl').prop('checked', !!userPermisionOrderItemUpdate);
        $('#editAccountModal #editAccountOrderItemDeletePermissionControl').prop('checked', !!userPermisionOrderItemDelete);

        $('#editAccountModal #editAccountDashboardViewPermissionControl').prop('checked', !!userPermisionDashboardView);
        $('#editAccountModal #editAccountSettingsViewPermissionControl').prop('checked', !!userPermisionSettingsView);
        $('#editAccountModal #editAccountItemPropertiesSettingsPermissionControl').prop('checked', !!userPermisionItemPropertiesSettings);
        $('#editAccountModal #editAccountOrderPropertiesSettingsPermissionControl').prop('checked', !!userPermisionOrderPropertiesSettings);
        $('#editAccountModal #editAccountPaginationPropertiesSettingsPermissionControl').prop('checked', !!userPermisionPaginationPropertiesSettings);

    }

    function readUserDataToDelete(element) {
        var fullName = $(element).data('user-name');
        var email = $(element).data('user-email');

        $('#deleteAccountModal #deleteAccountFullNameDisplay').html(fullName);
        $('#deleteAccountModal #deleteAccountEmailDisplay').html(email);
    }
</script>
@endsection('extraScript')

@section('content')

<?php

// Hardcoded Permissions
// class Permission
// {
//     public $columnName;
//     public $cnDisplayName;
//     public $elementId;
//     public $checkedByDefault;

//     function __construct($columnName, $cnDisplayName, $elementId, $checkedByDefault)
//     {
//         $this->columnName = $columnName;
//         $this->cnDisplayName = $cnDisplayName;
//         $this->elementId = $elementId;
//         $this->checkedByDefault = $checkedByDefault;
//     }
// }

// $permissions = array(
//     new Permission('item_view', '商品查看', 'ItemView', true),
//     new Permission('item_create', '商品创建', 'ItemCreate', false),
//     new Permission('item_update', '商品编辑', 'ItemUpdate', false),
//     new Permission('item_delete', '商品删除', 'ItemDelete', false),
//     new Permission('item_list', '商品上架', 'ItemListing', false),

//     new Permission('order_view', '订单查看', 'OrderView', true),
//     new Permission('order_update', '订单基本属性编辑', 'OrderUpdate', false),
//     new Permission('order_delete', '订单删除', 'OrderDelete', false),
//     new Permission('order_receipt_view', '顾客收据查看', 'OrderReceiptView', true),
//     new Permission('order_invoice_download', '订单详情下载', 'OrderInvoiceDownload', false),

//     new Permission('order_item_view', '订单商品查看', 'OrderItemView', true),
//     new Permission('order_item_create', '订单商品创建', 'OrderItemCreate', false),
//     new Permission('order_item_update', '订单商品编辑', 'OrderItemUpdate', false),
//     new Permission('order_item_delete', '订单商品删除', 'OrderItemDelete', false),

//     new Permission('dashboard_view', '仪表板查看', 'DashboardView', true),
//     new Permission('setting_view', '设定查看', 'SettingsView', true),
//     new Permission('setting_account', '账号设置', 'AccountSettings', true),
//     new Permission('setting_item', '商品相关设定', 'ItemPropertiesSettings', false),
//     new Permission('setting_order', '订单相关设定', 'OrderPropertiesSettings', false),
//     new Permission('setting_pagination', '显示数量相关设定', 'PaginationPropertiesSettings', false),
// );
?>

<main class="container">
    <div class="h1">员工账户管理</div>

    <!-- Alert Message -->
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissable" role="alert">
        {!! nl2br(e(session('message'))) !!}
    </div>
    @endif

    <div class="row-flex d-flex justify-content-end w-100 my-2">
        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addAccountModal">
            <i class="icofont icofont-ui-add icofont-1x mr-2"></i>添加员工账户
        </button>
    </div>

    <users :users={{ $users }} :permissions={{ json_encode($permissions, true) }}></users>
    
</main>

@endsection