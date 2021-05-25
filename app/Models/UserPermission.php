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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
