<?php

namespace App\Util;

use DateInterval;
use DateTime;
use Exception;

class SQLHelper
{
    public static function generateWhereClause($keyword, array $attrToFilter, string $mode = 'search'): string
    {
        $line = "";

        if ($keyword == "") return $line;

        switch ($mode){

            case "exact": // Search exact value

                foreach ($attrToFilter as $tableName => $attributes) {
                    foreach ($attributes as $key => $attributeName) {
                        $line = $line . ((array_key_first($attrToFilter) == $tableName && array_key_first($attributes) == $key) ? "" : " OR ");
                        $line = $line . "$tableName.$attributeName = '$keyword'";
                    }
                }

                break;

            case "date": // Search from date from 0000 to 2359

                foreach ($attrToFilter as $tableName => $attributes) {
                    foreach ($attributes as $key => $attributeName) {
                        try {
                            $date = new DateTime($keyword);
                            $start = $date->sub(new DateInterval('PT8H'))->format('Y-m-d H:i:s');
                            $end = $date->add(new DateInterval('PT24H'))->format('Y-m-d H:i:s');

                            $line = $line . ((array_key_first($attrToFilter) == $tableName && array_key_first($attributes) == $key) ? "" : " OR ");
                            $line = $line . "$tableName.$attributeName BETWEEN '$start' AND '$end'";
                        } catch (Exception $ignored){}
                    }
                }

                break;

            case "range": // Compare number range

                foreach ($attrToFilter as $tableName => $attributes) {
                    foreach ($attributes as $key => $attributeName) {
                        $line = $line . ((array_key_first($attrToFilter) == $tableName && array_key_first($attributes) == $key) ? "" : " OR ");
                        $line = $line . "$tableName.$attributeName BETWEEN '$keyword[0]' AND '$keyword[1]'";
                    }
                }

                break;

            default: // Search with keyword

                foreach ($attrToFilter as $tableName => $attributes) {
                    foreach ($attributes as $key => $attributeName) {
                        $line = $line . ((array_key_first($attrToFilter) == $tableName && array_key_first($attributes) == $key) ? "" : " OR ");
                        $line = $line . "$tableName.$attributeName LIKE '%$keyword%'";
                    }
                }
        }
        return $line;
    }

    public static function combineWhereClause(array $lines, string $connector = 'AND'): string
    {
        $line = "";

        // Skip empty line
        $lines = array_filter($lines, function ($l){
            return $l != "";
        });

        foreach ($lines as $key => $l) {
            $line = array_key_first($lines) == $key ? $line . "($l)" : $line . " $connector ($l)";
        }

        return $line;
    }

    public static function generateItemsArrangementClause(string $type): string
    {
        switch ($type){
            case "createdAtDesc":
                return "items.created_at DESC";
            case "createdAtAsc":
                return "items.created_at ASC";
            case "salesDesc":
                return "item_utils.sold DESC";
            case "salesAsc":
                return "item_utils.sold ASC";
            case "viewsDesc":
                return "item_utils.view_count DESC";
            case "viewsAsc":
                return "item_utils.view_count ASC";
            default:
                return "items.created_at";
        }
    }

    public static function generateParameter(array $attributes, bool $andSym = false): string
    {
        $line = "?";

        foreach ($attributes as $key => $value) {
            $line = array_key_last($attributes) == $key ? $line . "$key=$value" : $line . "$key=$value&";
        }

        return $andSym ? $line . '&' : $line;
    }
}
