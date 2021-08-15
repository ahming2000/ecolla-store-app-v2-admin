<?php

namespace App\Util;

class ViewHelper
{
    public static function paramSelected(string $param, $value): string
    {
        if (!empty($_GET[$param])){
            return $_GET[$param] == $value ? "selected" : "";
        } else {
            return "";
        }
    }

    public static function param(string $param, $default = "")
    {
        if (!empty($_GET[$param])){
            return $_GET[$param];
        } else {
            return $default;
        }
    }

}
