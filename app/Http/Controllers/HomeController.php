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
        $this->middleware('auth');
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

        $date_option_arr = HomeController::get_date_options($order_arr);

        //Get Date
        $daily_date = $request->input('day', $date_option_arr[0]);

        //Day Pipeline
        $daily_date_arr = HomeController::get_order_arr_date($order_arr, $daily_date);
        $daily_product_arr = HomeController::get_product_arr($daily_date_arr);
        $daily_product_count = (sizeof($daily_product_arr) == 0) ? 0 : HomeController::get_product_count($daily_product_arr);
        $daily_product_sales_revenue = (sizeof($daily_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($daily_product_arr);
        $daily_product_arr = (sizeof($daily_product_arr) > 7) ? array_slice($daily_product_arr, 0, 7) : $daily_product_arr;

        $week_option_arr = HomeController::get_week_options($order_arr);

        //Get Week
        $week_str = $request->input('week', $week_option_arr[0]);

        //Week Pipeline
        $week_arr = HomeController::get_order_arr_week($order_arr, $week_str);
        $week_product_arr = HomeController::get_product_arr($week_arr);
        $week_product_count = (sizeof($week_product_arr) == 0) ? 0 : HomeController::get_product_count($week_product_arr);
        $week_product_sales_revenue = (sizeof($week_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($week_product_arr);
        $week_product_arr = (sizeof($week_product_arr) > 7) ? array_slice($week_product_arr, 0, 7) : $week_product_arr;


        $month_option_arr = HomeController::get_month_options($order_arr);

        //Get Month
        $month_str = $request->input('month', $month_option_arr[0]);

        //Month Pipeline
        $month_arr = HomeController::get_order_arr_month($order_arr, $month_str);
        $month_product_arr = HomeController::get_product_arr($month_arr);
        $month_product_count = (sizeof($month_product_arr) == 0) ? 0 : HomeController::get_product_count($month_product_arr);
        $month_product_sales_revenue = (sizeof($month_product_arr) == 0) ? 0 : HomeController::get_product_sales_revenue($month_product_arr);
        $month_product_arr = (sizeof($month_product_arr) > 7) ? array_slice($month_product_arr, 0, 7) : $month_product_arr;


        return view('home', compact(
            'daily_date_arr', 'date_option_arr', 'daily_product_arr', 'daily_product_count', 'daily_product_sales_revenue', 
            'month_option_arr', 'month_arr', 'month_product_arr', 'month_product_count', 'month_product_sales_revenue', 
            'week_option_arr', 'week_arr', 'week_product_arr', 'week_product_count', 'week_product_sales_revenue'));
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
