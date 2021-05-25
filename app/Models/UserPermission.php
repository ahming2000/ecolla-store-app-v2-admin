<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    public static function getAllAttributes(): array
    {
        return [
            'item_view',
            'item_create',
            'item_update',
            'item_delete',
            'item_list',
            'order_view',
            'order_update',
            'order_delete',
            'order_receipt_view',
            'order_invoice_download',
            'order_item_view',
            'order_item_create',
            'order_item_update',
            'order_item_delete',
            'dashboard_view',
            'setting_view',
            'setting_item',
            'setting_order',
            'setting_pagination',
            'setting_account',
        ];
    }

    public static function getPermissions(): array
    {
        return [
            'item_view' => [
                'columnName' => 'item_view',
                'cnDisplayName' => '',
                'elementId' => 'ItemView',
                'checkedByDefault' => true,
            ],
            'item_create' => [
                'columnName' => 'item_create',
                'cnDisplayName' => '商品查看',
                'elementId' => 'ItemCreate',
                'checkedByDefault' => false,
            ],
            'item_update' => [
                'columnName' => 'item_update',
                'cnDisplayName' => '商品创建',
                'elementId' => 'ItemUpdate',
                'checkedByDefault' => false,
            ],
            'item_delete' => [
                'columnName' => 'item_delete',
                'cnDisplayName' => '商品编辑',
                'elementId' => 'ItemDelete',
                'checkedByDefault' => false,
            ],
            'item_list' => [
                'columnName' => 'item_list',
                'cnDisplayName' => '商品删除',
                'elementId' => 'ItemListing',
                'checkedByDefault' => false,
            ],
            'order_view' => [
                'columnName' => 'order_view',
                'cnDisplayName' => '商品上架',
                'elementId' => 'OrderView',
                'checkedByDefault' => true,
            ],
            'order_update' => [
                'columnName' => 'order_update',
                'cnDisplayName' => '订单查看',
                'elementId' => 'OrderUpdate',
                'checkedByDefault' => false,
            ],
            'order_delete' => [
                'columnName' => 'order_delete',
                'cnDisplayName' => '订单基本属性编辑',
                'elementId' => 'OrderDelete',
                'checkedByDefault' => false,
            ],
            'order_receipt_view' => [
                'columnName' => 'order_receipt_view',
                'cnDisplayName' => '订单删除',
                'elementId' => 'OrderReceiptView',
                'checkedByDefault' => true,
            ],
            'order_invoice_download' => [
                'columnName' => 'order_invoice_download',
                'cnDisplayName' => '顾客收据查看',
                'elementId' => 'OrderInvoiceDownload',
                'checkedByDefault' => false,
            ],
            'order_item_view' => [
                'columnName' => 'order_item_view',
                'cnDisplayName' => '订单详情下载',
                'elementId' => 'OrderItemView',
                'checkedByDefault' => true,
            ],
            'order_item_create' => [
                'columnName' => 'order_item_create',
                'cnDisplayName' => '订单商品查看',
                'elementId' => 'OrderItemCreate',
                'checkedByDefault' => false,
            ],
            'order_item_update' => [
                'columnName' => 'order_item_update',
                'cnDisplayName' => '订单商品创建',
                'elementId' => 'OrderItemUpdate',
                'checkedByDefault' => false,
            ],
            'order_item_delete' => [
                'columnName' => 'order_item_delete',
                'cnDisplayName' => '订单商品编辑',
                'elementId' => 'OrderItemDelete',
                'checkedByDefault' => false,
            ],
            'dashboard_view' => [
                'columnName' => 'dashboard_view',
                'cnDisplayName' => '订单商品删除',
                'elementId' => 'DashboardView',
                'checkedByDefault' => true,
            ],
            'setting_view' => [
                'columnName' => 'setting_view',
                'cnDisplayName' => '仪表板查看',
                'elementId' => 'SettingsView',
                'checkedByDefault' => true,
            ],
            'setting_item' => [
                'columnName' => 'setting_item',
                'cnDisplayName' => '设定查看',
                'elementId' => 'AccountSettings',
                'checkedByDefault' => true,
            ],
            'setting_order' => [
                'columnName' => 'setting_order',
                'cnDisplayName' => '账号设置',
                'elementId' => 'ItemPropertiesSettings',
                'checkedByDefault' => false,
            ],
            'setting_pagination' => [
                'columnName' => 'setting_pagination',
                'cnDisplayName' => '商品相关设定',
                'elementId' => 'OrderPropertiesSettings',
                'checkedByDefault' => false,
            ],
            'setting_account' => [
                'columnName' => 'setting_account',
                'cnDisplayName' => '订单相关设定',
                'elementId' => 'PaginationPropertiesSettings',
                'checkedByDefault' => false,
            ],
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
