<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
        foreach (Controller::$infoMessageList as $m){
            $msg = $msg . $m . "\n";
        }
        Controller::$infoMessageList = [];
        return $msg;
    }

    protected function pullError(): string
    {
        $msg = "";
        foreach (Controller::$errorMessageList as $m){
            $msg = $msg . $m . "\n";
        }
        Controller::$errorMessageList = [];
        return $msg;
    }

    protected function stackMessage(string $message){
        Controller::$infoMessageList[] = $message;
    }

    protected function stackError(string $error){
        Controller::$errorMessageList[] = $error;
    }

}
