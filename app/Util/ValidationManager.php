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

    public function validateUnique(string $table, string $column, string $curVal, string $newVal, string $errorMessage = "该资料已存在数据库！")
    {
        $isNotUnique = DB::table($table)->select($column)->where($column, '!=', $curVal)->where($column, '=', $newVal)->first() == null;
        if ($isNotUnique) $this->errors[$column] = $errorMessage;
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
