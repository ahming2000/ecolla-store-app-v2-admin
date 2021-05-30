<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access:status_check']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $test = DB::select("SELECT * FROM items");
        return view('home', compact('test'));
    }

    public function homeDoGet(Request $request)
    {
        //1. Get Object Array Based on Date, Week, Month
        $order_arr = DB::select("SELECT * FROM orders");

        //Update $order_arr
        $order_arr = HomeController::updateOrderDateTime($order_arr);

        $date_option_arr = HomeController::get_date_options($order_arr);

        $first_date = empty($date_option_arr) ? date("Y-m-d") : $date_option_arr[0];
        //Get Date
        $daily_date = $request->input('day', $first_date);

        //Day Pipeline
        $daily_date_arr = HomeController::get_order_arr_date($order_arr, $daily_date);
        $daily_product_arr = HomeController::get_product_arr($daily_date_arr);
        $daily_product_count = (sizeof($daily_product_arr) == 0) ? 0 : HomeController::get_product_count($daily_product_arr);
        $daily_product_sales_revenue = (sizeof($daily_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($daily_product_arr);
        $daily_product_arr = (sizeof($daily_product_arr) > 7) ? array_slice($daily_product_arr, 0, 7) : $daily_product_arr;

        $daily_hashmap = HomeController::get_time_graph_hashmap($daily_date_arr);
        $daily_graph_arr = HomeController::get_graph_arr($daily_hashmap);

        $week_option_arr = HomeController::get_week_options($order_arr);

        $first_week = empty($week_option_arr) ? HomeController::check_which_week(date("d/m/Y")) : $week_option_arr[0];
        //Get Week
        $week_str = $request->input('week', $first_week);

        //Week Pipeline
        $week_arr = HomeController::get_order_arr_week($order_arr, $week_str);
        $week_product_arr = HomeController::get_product_arr($week_arr);
        $week_product_count = (sizeof($week_product_arr) == 0) ? 0 : HomeController::get_product_count($week_product_arr);
        $week_product_sales_revenue = (sizeof($week_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($week_product_arr);
        $week_product_arr = (sizeof($week_product_arr) > 7) ? array_slice($week_product_arr, 0, 7) : $week_product_arr;

        $week_hashmap = HomeController::get_week_graph_hashmap($week_arr, $week_str);
        $week_graph_arr = HomeController::get_graph_arr($week_hashmap);

        $month_option_arr = HomeController::get_month_options($order_arr);

        $first_month = empty($month_option_arr) ? date("M") . " " . date("Y") : $month_option_arr[0];
        //Get Month
        $month_str = $request->input('month', $first_month);

        //Month Pipeline
        $month_arr = HomeController::get_order_arr_month($order_arr, $month_str);
        $month_product_arr = HomeController::get_product_arr($month_arr);
        $month_product_count = (sizeof($month_product_arr) == 0) ? 0 : HomeController::get_product_count($month_product_arr);
        $month_product_sales_revenue = (sizeof($month_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($month_product_arr);
        $month_product_arr = (sizeof($month_product_arr) > 7) ? array_slice($month_product_arr, 0, 7) : $month_product_arr;

        $month_hashmap = HomeController::get_month_graph_hashmap($month_arr, $month_str);
        $month_graph_arr = HomeController::get_graph_arr($month_hashmap);

        $tab_active = $request->input('type', "daily");

        return view('home', compact(
            'daily_date_arr', 'date_option_arr', 'daily_product_arr', 'daily_product_count', 'daily_product_sales_revenue', 'daily_graph_arr',
            'month_option_arr', 'month_arr', 'month_product_arr', 'month_product_count', 'month_product_sales_revenue', 'month_graph_arr',
            'week_option_arr', 'week_arr', 'week_product_arr', 'week_product_count', 'week_product_sales_revenue', 'week_graph_arr'
            ,'tab_active'));
    }

    static function getOrderCreateDateTime($date_time)
    {
        $result = date_add(new DateTime($date_time), new DateInterval('PT8H')); // GMT +8
        return $result->format('Y-m-d H:i:s');
    }

    static function updateOrderDateTime($order_arr){
        $arr = array_map(function($obj) {
            $obj->created_at = HomeController::getOrderCreateDateTime($obj->created_at);
            return $obj;
        }, $order_arr);
        return $arr;
    }

    static function get_date_options($order_arr) {
        $arr = array_map(function($obj) {
            return date('Y-m-d', strtotime($obj->created_at));
        }, $order_arr);
        return array_unique($arr);
    }

    static function get_week_options($order_arr) {
        $arr = array_map(function($obj) {
            $date = new DateTime(date('d-m-Y', strtotime($obj->created_at)));
            return HomeController::check_which_week($date->format('d/m/Y'));
        }, $order_arr);
        return array_unique($arr);
    }

    static function get_month_options($order_arr){
        $arr = array_map(function($obj) {
            return date('M', strtotime($obj->created_at)) . " " . date('Y', strtotime($obj->created_at));
        }, $order_arr);
        return array_unique($arr);
    }

    static function check_time_group($time_stamp) {
        $hour = date('H', strtotime($time_stamp));
        if($hour < 6) return "0:01 - 6:00";
        else if($hour >= 6 && $hour < 12) return "6:01 - 12:00";
        else if ($hour >= 12 && $hour < 18) return "12:01 - 18:00";
        return "18:01 - 0:00";
    }

    static function get_order_arr_time($order_arr, $time_range) {
        $arr = array_filter($order_arr, function($obj) use ($time_range) {
            $time_match = HomeController::check_time_group($obj->created_at);
            return $time_match == $time_range;
        });
        return $arr;
    }

    static function get_order_arr_date($order_arr, $daily_date){
        $arr = array_filter($order_arr, function($obj) use ($daily_date) {
            $date_match = date('Y-m-d', strtotime($obj->created_at));
            return $daily_date == $date_match;
        });
        return $arr;
    }

    static function get_order_arr_week($order_arr, $week_str){
        $arr = array_filter($order_arr, function($obj) use ($week_str) {
            $date = new DateTime(date('d-m-Y', strtotime($obj->created_at)));
            return HomeController::check_which_week($date->format('d/m/Y')) == $week_str;
        });
        return $arr;
    }

    static function get_order_arr_month($order_arr, $month_str) {
        $tmp_split = explode(" ", $month_str);
        $month_chosen = $tmp_split[0];
        $year_chosen = $tmp_split[1];

        $arr = array_filter($order_arr, function($obj) use ($month_chosen, $year_chosen) {
            $month_match = date('M', strtotime($obj->created_at));
            $year_match = date('Y', strtotime($obj->created_at));
            return $month_match == $month_chosen && $year_match == $year_chosen;
        });

        return $arr;
    }

    static function get_graph_arr($hashmap){
        $str = "";
        $str2 = "";
        $str3 = "";
        $graph_arr = $hashmap;
        $label_arr = array_keys($graph_arr);

        foreach($label_arr as $label){
            $str .= $label . "/" . $graph_arr[$label][2] . "/";
            $str2 .= $label . "/" . $graph_arr[$label][1] . "/";
            $str3 .= $label . "/" . $graph_arr[$label][0] . "/";
        }

        $str = substr($str, 0, strlen($str) - 1);
        $str2 = substr($str2, 0, strlen($str2) - 1);
        $str3 = substr($str3, 0, strlen($str3) - 1);

        return array($str, $str2, $str3);
    }

    static function get_month_graph_hashmap($month_arr, $month_str) {
        $tmp_split = explode(" ", $month_str);
        $month = $tmp_split[0];
        $year = $tmp_split[1];

        $arr = HomeController::get_all_week_option($month_str);
        $hashmap = [];

        foreach($arr as $week_str){
            $week_arr = HomeController::get_order_arr_week($month_arr, $week_str);
            $week_product_arr = HomeController::get_product_arr($week_arr);
            $week_product_count = (sizeof($week_product_arr) == 0) ? 0 : HomeController::get_product_count($week_product_arr);
            $week_product_sales_revenue = (sizeof($week_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($week_product_arr);
            $tmp_split = explode(" ", $week_str);
            $hashmap[$tmp_split[0] . " " . $tmp_split[1]] = array(sizeof($week_arr), $week_product_count, $week_product_sales_revenue);
        }

        return $hashmap;
    }

    static function get_week_graph_hashmap($week_arr, $week_str){
        $year = substr(explode(" ", $week_str)[3], 0, 4);
        $month = substr(explode(" ", $week_str)[3], -2, 2);
        $all_date_arr = HomeController::weeks_in_month($month, $year)[explode("(", $week_str)[0]];

        $hashmap = [];

        foreach($all_date_arr as $date){
            $date = date('Y-m-d', strtotime(str_replace('/', '-', $date)));
            $daily_date_arr = HomeController::get_order_arr_date($week_arr, $date);
            $daily_product_arr = HomeController::get_product_arr($daily_date_arr);
            $daily_product_count = (sizeof($daily_product_arr) == 0) ? 0 : HomeController::get_product_count($daily_product_arr);
            $daily_product_sales_revenue = (sizeof($daily_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($daily_product_arr);
            $hashmap[$date] = array(sizeof($daily_date_arr), $daily_product_count, $daily_product_sales_revenue);
        }
        return $hashmap;
    }

    static function get_time_graph_hashmap($daily_date_arr) {
        $time_options = array("0:01 - 6:00", "6:01 - 12:00", "12:01 - 18:00", "18:01 - 0:00");

        $hashmap = [];

        foreach($time_options as $time_range){
            $time_arr = HomeController::get_order_arr_time($daily_date_arr, $time_range);
            $time_product_arr = HomeController::get_product_arr($time_arr);
            $time_product_count = (sizeof($time_product_arr) == 0) ? 0 : HomeController::get_product_count($time_product_arr);
            $time_product_sales_revenue = (sizeof($time_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($time_product_arr);
            $hashmap[$time_range] = array(sizeof($time_arr), $time_product_count, $time_product_sales_revenue);
        }
        return $hashmap;
    }

    static function get_all_week_option($month_str){
        $tmp_split = explode(" ", $month_str);
        $month = $tmp_split[0];
        $year = $tmp_split[1];
        $week_arr = HomeController::weeks_in_month($month, $year);

        $arr = [];
        foreach(array_keys($week_arr) as $key) array_push($arr, $key . "(" . substr($week_arr[$key][0], 0, -5) . " - " . substr(end($week_arr[$key]), 0, -5) . ")");

        return $arr;
    }

    static function get_product_arr($order_arr){
        $arr = [];
        foreach($order_arr as $order){
            $tmp_arr = DB::select("SELECT * FROM order_items WHERE order_id = " . $order->id);
            $arr = array_merge($arr, $tmp_arr);
        }

        //Remove Duplicates
        $arr = HomeController::add_duplicates_tgt($arr);

        //Sort By Quantity
        usort($arr, function($a, $b) {
            return $a->quantity <= $b->quantity;
        });

        return $arr;
    }

    static function add_duplicates_tgt($order_item_arr){
        $arr = [];
        foreach($order_item_arr as $order_item){
            if(array_key_exists($order_item->barcode,$arr))
                $arr[$order_item->barcode]->quantity += 90;
            else
                $arr[$order_item->barcode] = $order_item;
        }
        return $arr;
    }

    static function get_product_count($product_arr){
        $num = array_reduce($product_arr, function($i, $obj) {
            return $i += $obj->quantity;
        });
        return $num;
    }

    static function get_product_sales_revenue($product_arr) {
        $sum = array_reduce($product_arr, function($i, $obj){
            return $i += $obj->quantity * $obj->price;
        });
        return $sum;
    }

    static function check_which_week($date) {
        $tmp_date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        $week_arr = HomeController::weeks_in_month(date('m', strtotime($tmp_date)), date('Y', strtotime($tmp_date)));
        $i = 0;
        foreach($week_arr as $week){
            if(in_array($date, $week)) break;
            $i++;
        }

        $key = array_keys($week_arr)[$i];
        return $key . "(" . substr($week_arr[$key][0], 0, -5) . " - " . substr(end($week_arr[$key]), 0, -5) . ")";
    }

    static function weeks_in_month($month, $year)
    {
        $dates = [];

        $week = 1;
        $date = new DateTime("$year-$month-01");
        $days = (int)$date->format('t'); // total number of days in the month

        $oneDay = new DateInterval('P1D');

        for ($day = 1; $day <= $days; $day++) {
            $dates["Week $week ". date('M', strtotime("27-" . $month . "-2021")) ." $year"][] = $date->format('d/m/Y');

            $dayOfWeek = $date->format('l');
            if ($dayOfWeek === 'Saturday') {
                $week++;
            }

            $date->add($oneDay);
        }

        return $dates;
    }
}
;
