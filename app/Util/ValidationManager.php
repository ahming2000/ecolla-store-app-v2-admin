<?php

namespace App\Util;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ValidationManager
{
    private array $errors = [];

    public function __construct(array $data = [], array $rules = [])
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $this->errors = $validator->errors()->getMessages();
        }
    }

    public static function validate(array $data = [], array $rules = []): array
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $validator->errors()->getMessages();
        } else {
            return [];
        }
    }

    public function validateUniqueBarcode(string $itemId, string $barcode)
    {
        // Check other item's variation barcode
        $result = DB::table('variations')
            ->select('id')
            ->where('barcode', '=', $barcode)
            ->where('item_id', '!=', $itemId)
            ->get(); // Validate success -> Result not found

        if (!empty($result->toArray())){
            $selected = $result->toArray()[0]->id;
            $this->errors['barcode'] = "货号 <b>$barcode</b> 已重复在其他商品里！<a href='/item/$selected/edit'>点击</a>进行跳转。";
            return;
        }

        // Check current item's variation barcode
        $result = DB::table('variations')
            ->select('id')
            ->where('barcode', '=', $barcode)
            ->where('item_id', '=', $itemId)
            ->get(); // Validate success -> Result not found

        if (!empty($result->toArray())){
            $this->errors['barcode'] = "货号 <b>$barcode</b> 已重复！";
        }
    }

    public function hasError(): bool
    {
        return !empty($this->errors);
    }

    public function getErrorMessage(): array
    {
        return $this->errors;
    }
}
