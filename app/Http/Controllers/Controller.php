<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * @param array $array
     * @param string $arrayKey
     * @return array
     */
    protected function generateArrayKeyFromElement(array $array, string $arrayKey): array
    {
        $temp = [];
        foreach($array as $arr){
            $arrayKeyValue = $arr["{$arrayKey}"];
            $temp[$arrayKeyValue] = $arr;
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
        for($i = -1; $i > strlen($oldFileName) * -1; $i--){
            if($oldFileName[$i] == '.'){ // Find the point to start the count after extension
                $flag = true;
            }
            if($flag){
                $count++;
            }
            if($oldFileName[$i] == '/'){ // Stop at '/'
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


}
