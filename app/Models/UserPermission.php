<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $guarded = [];

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
            //            'order_item_create',
            //            'order_item_update',
            //            'order_item_delete',
            'dashboard_view',
            'setting_view',
            'setting_item',
            'setting_order',
            'setting_account',
        ];
    }

    public static function getPermissions(): array
    {
        return [
            [
                'parent' => 'root',
                'groupName' => 'item',
                'permissions' => [
                    [
                        'columnName' => 'item_view',
                        'cnDisplayName' => '商品查看',
                        'elementId' => 'ItemView',
                        'checkedByDefault' => true,
                    ], [
                        'columnName' => 'item_create',
                        'cnDisplayName' => '商品创建',
                        'elementId' => 'ItemCreate',
                        'checkedByDefault' => false,
                    ], [
                        'columnName' => 'item_update',
                        'cnDisplayName' => '商品编辑',
                        'elementId' => 'ItemUpdate',
                        'checkedByDefault' => false,
                    ], [
                        'columnName' => 'item_delete',
                        'cnDisplayName' => '商品删除',
                        'elementId' => 'ItemDelete',
                        'checkedByDefault' => false,
                    ], [
                        'columnName' => 'item_list',
                        'cnDisplayName' => '商品上架',
                        'elementId' => 'ItemListing',
                        'checkedByDefault' => false,
                    ],
                ],
                'subGroups' => [],
            ], [
                'parent' => 'root',
                'groupName' => 'order',
                'permissions' => [
                    [
                        'columnName' => 'order_view',
                        'cnDisplayName' => '订单查看',
                        'elementId' => 'OrderView',
                        'checkedByDefault' => true,
                    ], [
                        'columnName' => 'order_update',
                        'cnDisplayName' => '订单基本属性编辑',
                        'elementId' => 'OrderUpdate',
                        'checkedByDefault' => false,
                    ], [
                        'columnName' => 'order_delete',
                        'cnDisplayName' => '订单删除',
                        'elementId' => 'OrderDelete',
                        'checkedByDefault' => false,
                    ], [
                        'columnName' => 'order_receipt_view',
                        'cnDisplayName' => '顾客收据查看',
                        'elementId' => 'OrderReceiptView',
                        'checkedByDefault' => true,
                    ], [
                        'columnName' => 'order_invoice_download',
                        'cnDisplayName' => '订单详情下载',
                        'elementId' => 'OrderInvoiceDownload',
                        'checkedByDefault' => false,
                    ],
                ],
                'subGroups' => [
                    [
                        'parent' => 'order',
                        'groupName' => 'order_item',
                        'permissions' => [
                            [
                                'columnName' => 'order_item_view',
                                'cnDisplayName' => '订单商品查看',
                                'elementId' => 'OrderItemView',
                                'checkedByDefault' => true,
                            ],
                            // Uncomment to display permissions
                            // [
                            //     'columnName' => 'order_item_create',
                            //     'cnDisplayName' => '订单商品创建',
                            //     'elementId' => 'OrderItemCreate',
                            //     'checkedByDefault' => false,
                            // ], [
                            //     'columnName' => 'order_item_update',
                            //     'cnDisplayName' => '订单商品编辑',
                            //     'elementId' => 'OrderItemUpdate',
                            //     'checkedByDefault' => false,
                            // ], [
                            //     'columnName' => 'order_item_delete',
                            //     'cnDisplayName' => '订单商品删除',
                            //     'elementId' => 'OrderItemDelete',
                            //     'checkedByDefault' => false,
                            // ],
                        ],
                        'subGroups' => [],
                    ],
                ],
            ], [
                'parent' => 'root',
                'groupName' => 'dashboard',
                'permissions' => [
                    [
                        'columnName' => 'dashboard_view',
                        'cnDisplayName' => '仪表板查看',
                        'elementId' => 'DashboardView',
                        'checkedByDefault' => true,
                    ],
                ],
                'subGroups' => [],
            ], [
                'parent' => 'root',
                'groupName' => 'setting',
                'permissions' => [
                    [
                        'columnName' => 'setting_view',
                        'cnDisplayName' => '设定查看',
                        'elementId' => 'SettingsView',
                        'checkedByDefault' => true,
                    ], [
                        'columnName' => 'setting_item',
                        'cnDisplayName' => '商品相关设定',
                        'elementId' => 'ItemPropertiesSettings',
                        'checkedByDefault' => true,
                    ], [
                        'columnName' => 'setting_order',
                        'cnDisplayName' => '订单相关设定',
                        'elementId' => 'OrderPropertiesSettings',
                        'checkedByDefault' => false,
                    ], [
                        'columnName' => 'setting_account',
                        'cnDisplayName' => '账号设置',
                        'elementId' => 'AccountSettings',
                        'checkedByDefault' => false,
                    ],
                ],
                'subGroups' => [],
            ],
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
