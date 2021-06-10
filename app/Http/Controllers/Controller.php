<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected array $ITEM_SEARCH = [
        'items' => [
            'name',
            'name_en',
            'origin',
            'origin_en',
            'desc',
        ],
        'variations' => [
            'name',
            'name_en',
            'barcode',
        ],
    ];

    protected array $ITEM_FILTER_CATEGORY = [
        'categories' => [
            'name',
            'name_en',
        ],
    ];

    protected array $ORDER_FILTER_CREATED_AT = [
        'orders' => [
            'created_at',
        ]
    ];

    protected array $ORDER_FILTER_MODE = [
        'orders' => [
            'mode',
        ]
    ];

    protected static array $infoMessageList = [];
    protected static array $errorMessageList = [];

    /**
     * @param array $array
     * @param string $arrayKey
     * @return array
     */
    protected function generateArrayKeyFromElement(array $array, string $arrayKey): array
    {
        $temp = [];
        foreach ($array as $arr) {
            if (array_key_exists($arr["{$arrayKey}"], $temp)) {
                $this->stackError("货号：" . $arr['barcode'] . " 已存在数据库！");
            } else {
                $arrayKeyValue = $arr["{$arrayKey}"];
                $temp[$arrayKeyValue] = $arr;
            }
        }
        return $temp;
    }

    /**
     * @param string $oldFileName
     * @return string
     */
    protected function getFilenameFromLink(string $oldFileName): string
    {
        $count = 0;
        $flag = false;
        for ($i = -1; $i > strlen($oldFileName) * -1; $i--) {
            if ($oldFileName[$i] == '.') { // Find the point to start the count after extension
                $flag = true;
            }
            if ($flag) {
                $count++;
            }
            if ($oldFileName[$i] == '/') { // Stop at '/'
                return substr($oldFileName, $i + 1, $count - 2);
            }
        }
        return "";
    }

    /**
     * @param $array
     * @param $key
     * @param $searchFor
     * @return array
     */
    protected function getArrayByKey($array, $key, $searchFor): array
    {
        return array_filter($array, function ($element) use ($key, $searchFor) {
            return isset($element[$key]) && $element[$key] == $searchFor;
        });
    }

    protected function hasMessage(): bool
    {
        return !empty(Controller::$infoMessageList);
    }

    protected function hasError(): bool
    {
        return !empty(Controller::$errorMessageList);
    }

    protected function pullMessage(): string
    {
        $msg = "";
        foreach (Controller::$infoMessageList as $m) {
            $msg = $msg . $m . "\n";
        }
        Controller::$infoMessageList = [];
        return $msg;
    }

    protected function pullError(): string
    {
        $msg = "";
        foreach (Controller::$errorMessageList as $m) {
            $msg = $msg . $m . "\n";
        }
        Controller::$errorMessageList = [];
        return $msg;
    }

    protected function stackMessage(string $message)
    {
        Controller::$infoMessageList[] = $message;
    }

    protected function stackError(string $error)
    {
        Controller::$errorMessageList[] = $error;
    }

    /**
     * @param string $keyword
     * @param array $attrToSearch
     * @return string
     */
    protected function generateSearchClause(string $keyword, array $attrToSearch): string
    {
        $isFirst = true;
        $line = "";
        if ($keyword != "") {
            foreach ($attrToSearch as $tableName => $attributes) {
                foreach ($attributes as $attributeName) {
                    if ($isFirst) {
                        $line = $line . "$tableName.$attributeName LIKE '%$keyword%'";
                        $isFirst = false;
                    } else {
                        $line = $line . " OR $tableName.$attributeName LIKE '%$keyword%'";
                    }
                }
            }
        }
        return $line;
    }

    /**
     * @param string $keyword
     * @param array $attrToFilter
     * @param bool $isDate
     * @return string
     */
    protected function generateFilterClause(string $keyword, array $attrToFilter, bool $isDate = false): string
    {
        $isFirst = true;
        $line = "";
        if ($keyword != "") {
            foreach ($attrToFilter as $tableName => $attributes) {
                foreach ($attributes as $attributeName) {
                    if($isDate){
                        if ($isFirst) {
                            $line = $line . "$tableName.$attributeName LIKE '$keyword%'";
                            $isFirst = false;
                        } else {
                            $line = $line . " OR $tableName.$attributeName LIKE '$keyword%'";
                        }
                    } else{
                        if ($isFirst) {
                            $line = $line . "$tableName.$attributeName = '$keyword'";
                            $isFirst = false;
                        } else {
                            $line = $line . " OR $tableName.$attributeName = '$keyword'";
                        }
                    }
                }
            }
        }
        return $line;
    }

    /**
     * @param array $lines
     * @param string $connector
     * @return string
     */
    protected function combineWhereClause(array $lines, string $connector = 'AND'): string
    {
        $line = "";

        $lines = array_filter($lines, function ($l){
            return $l != "";
        });

        foreach ($lines as $key => $l) {
            $line = array_key_first($lines) == $key ? $line . "($l)" : $line . " $connector ($l)";
        }

        return $line;
    }

    /**
     * @param array $attributes
     * @param bool $andSym
     * @return string
     */
    protected function generateParameter(array $attributes, bool $andSym = false): string
    {
        $line = "?";

        $attributes = array_filter($attributes, function ($value){
            return $value != "";
        });

        foreach ($attributes as $key => $value) {
            $line = array_key_first($attributes) == $key ? $line . "$key=$value&" : $line . "$key=$value";
        }

        return $andSym ? $line . '&' : $line;
    }
}
