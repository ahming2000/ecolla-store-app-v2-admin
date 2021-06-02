<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;

class HomeController extends Controller
{
    private int $num_of_items = 5;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function performUpdate(){
        echo 'Category Item attribute update process starting...<br>';
        DB::table('category_item')->where('category_id', '=', '5')->update(['category_id' => '14']);
        DB::table('category_item')->where('category_id', '=', '6')->update(['category_id' => '17']);
        DB::table('category_item')->where('category_id', '=', '8')->update(['category_id' => '19']);
        DB::table('category_item')->where('category_id', '=', '9')->update(['category_id' => '20']);
        DB::table('category_item')->where('category_id', '=', '10')->update(['category_id' => '21']);
        echo 'Category Item attribute update completed.<br>';



        die('Task completed.');
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
        // Get Order Arrays => All Existing Orders, Completed Orders, Cancelled Orders
        $all_order_arr = DB::select("SELECT * FROM orders");
        $completed_order_arr = DB::select("SELECT * FROM orders WHERE status = 'completed'");
        $canceled_order_arr = DB::select("SELECT * FROM orders WHERE status IN ('canceled', 'refunded')");

        // Update Order Arrays => Add 8 Hour Time to the Order Arrays
        $all_order_arr = $this->updateOrderDateTime($all_order_arr);
        $completed_order_arr = $this->updateOrderDateTime($completed_order_arr);
        $canceled_order_arr = $this->updateOrderDateTime($canceled_order_arr);

        // Get Options => Daily, Weekly, Monthly
        $daily_option_arr = $this->get_date_options($all_order_arr);
        $week_option_arr = $this->get_week_options($all_order_arr);
        $month_option_arr = $this->get_month_options($all_order_arr);

        // Options in String Format => To be Read by Javascript
        $daily_option_str = implode(" ", $daily_option_arr);
        $week_option_str = implode(" ", $week_option_arr);
        $month_option_str = $this->get_month_option_str($month_option_arr);

        // Get the Default Value when First accessing the dashboard
        // If the array is empty, it is set as today's date, week or month
        // If the array is not empty, it is set to the last value of the array
        // Format: 2021-05-15, 2021-20, May 2021
        $first_date = empty($daily_option_arr) ? date("Y-m-d") : end($daily_option_arr);
        $first_week = empty($week_option_arr) ? date('Y')."-".date('W') : end($week_option_arr);
        $first_month = empty($month_option_arr) ? date("M") . " " . date("Y") : end($month_option_arr);

        // Get Date, Week, Month
        // Returns the value of String used to Filter Order Array
        $daily_str = $request->input('day', $first_date);
        $week_str = $request->input('week', $first_week);
        $month_str = $request->input('month', $first_month);

        // Pipeline
        // 1. Get Daily, Week and Month Order Array Based on daily_str, week_str and month_str
        $daily_order_arr = $this->get_order_arr_date($completed_order_arr, $daily_str);
        $week_order_arr = $this->get_order_arr_week($completed_order_arr, $week_str);
        $month_order_arr = $this->get_order_arr_month($completed_order_arr, $month_str);

        // Canceled Date Order
        // Get Canceled Date Order Based on daily_str, week_str and month_str
        $canceled_daily_order_arr = $this->get_order_arr_date($canceled_order_arr, $daily_str);
        $canceled_week_order_arr = $this->get_order_arr_week($canceled_order_arr, $week_str);
        $canceled_month_arr = $this->get_order_arr_month($canceled_order_arr, $month_str);

        // 2. Get Info Array
        // Info Array Contains Number of Orders, Number of Product, Total Sales Revenue and Product Array
        $daily_info_arr = $this->get_product_arr_count_sales($daily_order_arr);
        $week_info_arr = $this->get_product_arr_count_sales($week_order_arr);
        $month_info_arr = $this->get_product_arr_count_sales($month_order_arr);

        // Get Canceled Info Array
        $canceled_daily_info_arr = $this->get_product_arr_count_sales($canceled_daily_order_arr);
        $canceled_week_info_arr = $this->get_product_arr_count_sales($canceled_week_order_arr);
        $canceled_month_info_arr = $this->get_product_arr_count_sales($canceled_month_arr);

        // 3. Get Order Count / Number of Orders Based on Daily, Week and Month
        $daily_order_count = $daily_info_arr[0];
        $week_order_count = $week_info_arr[0];
        $month_order_count = $month_info_arr[0];

        // 4. Get Product Count / Number of Products Based on Daily, Week and Month
        $daily_product_count = $daily_info_arr[1];
        $week_product_count = $week_info_arr[1];
        $month_product_count = $month_info_arr[1];

        // 5. Get Total Sales Revenue Based on Daily, Week and Month
        $daily_product_sales_revenue = $daily_info_arr[2];
        $week_product_sales_revenue = $week_info_arr[2];
        $month_product_sales_revenue = $month_info_arr[2];

        // Canceled Total Sales Revenue
        $canceled_daily_product_sales_revenue = $canceled_daily_info_arr[2];
        $canceled_week_product_sales_revenue = $canceled_week_info_arr[2];
        $canceled_month_product_sales_revenue = $canceled_month_info_arr[2];

        // 6. Get Product Array Based on Daily, Week and Month
        $daily_product_arr = $daily_info_arr[3];
        $week_product_arr = $week_info_arr[3];
        $month_product_arr = $month_info_arr[3];

        // 7. Get Hashmap Based on Daily, Week And Month
        // Hashmap is for the graph, it sorts the daily, week and month order array according to timestamp, daily and weekly
        // Hashmap contains the Number of Orders, Number of Products and Total Sales Revenue Based on the TimeStamp, Daily and Week
        $daily_hashmap = $this->get_time_graph_hashmap($daily_order_arr);
        $week_hashmap = $this->get_week_graph_hashmap($week_order_arr, $week_str);
        $month_hashmap = $this->get_month_graph_hashmap($month_order_arr, $month_str);

        // 8. Get Graph Array Based on Daily, Weekly and Monthly
        // The graph array contains the number of orders, number of products and total sales revenue
        // The Array contains the string format of the hashmap information
        // 0 => Number of Orders (String format), 1 => Number of Products (String format), 2 => Total Sales Revenue (String format)
        $daily_graph_arr = $this->get_graph_arr($daily_hashmap);
        $week_graph_arr = $this->get_graph_arr($week_hashmap);
        $month_graph_arr = $this->get_graph_arr($month_hashmap);

        // Tab Active controls which tab the dashboard shows to the user
        $tab_active = $request->input('type', "daily");

        // $test = $this->get_order_arr_week_test($order_arr, "2021-20");

        return view('home', compact(
            'daily_option_str','week_option_str','month_option_str',
            'daily_order_count', 'week_order_count', 'month_order_count',
            'daily_product_count', 'week_product_count', 'month_product_count',
            'daily_product_sales_revenue', 'week_product_sales_revenue', 'month_product_sales_revenue',
            'canceled_daily_product_sales_revenue', 'canceled_week_product_sales_revenue', 'canceled_month_product_sales_revenue',
            'daily_product_arr', 'week_product_arr', 'month_product_arr',
            'daily_graph_arr', 'week_graph_arr', 'month_graph_arr',
            'tab_active'
        ));
    }

    // Add 8 Hours to Order
    function getOrderCreateDateTime($date_time)
    {
        $result = date_add(new DateTime($date_time), new DateInterval('PT8H')); // GMT +8
        return $result->format('Y-m-d H:i:s');
    }

    function updateOrderDateTime($order_arr)
    {
        $arr = array_map(function ($obj) {
            $obj->created_at = $this->getOrderCreateDateTime($obj->created_at);
            return $obj;
        }, $order_arr);
        return $arr;
    }

    // 0. Extra Function
    /**
     * check_time_group
     *
     * @param  String $time_stamp
     * @return String
     */
    function check_time_group($time_stamp)
    {
        $hour = date('H', strtotime($time_stamp));
        if ($hour < 6) return "0:01 - 6:00";
        else if ($hour >= 6 && $hour < 12) return "6:01 - 12:00";
        else if ($hour >= 12 && $hour < 18) return "12:01 - 18:00";
        return "18:01 - 0:00";
    }

    /**
     * add_duplicates_tgt
     *
     * @param  Array $order_item_arr
     * @return Array
     */
    function add_duplicates_tgt($order_item_arr)
    {
        $arr = [];
        foreach ($order_item_arr as $order_item) {
            if (array_key_exists($order_item->barcode, $arr))
                $arr[$order_item->barcode]->quantity += $order_item->quantity;
            else
                $arr[$order_item->barcode] = $order_item;
        }
        return $arr;
    }

    /**
     * get_dates_from_week_year_arr
     *
     * @param  String $week_str
     * @return Array
     */
    // Gets an Array of Days based on the week number and the year
    function get_dates_from_week_year_arr($week_str)
    {
        $tmp_split = explode("-", $week_str);
        $year_chosen = $tmp_split[0];
        $week_chosen = $tmp_split[1];

        $dt = new DateTime();
        $dt->setIsoDate($year_chosen, $week_chosen);
        $arr = [$dt->format('Y-m-d')];
        for ($i = 1; $i <= 6; $i++) {
            $dt->modify('+1 day');
            array_push($arr, $dt->format('Y-m-d'));
        }
        return $arr;
    }

    /**
     * get_weeks_from_month_year_arr
     *
     * @param  String $month_str
     * @return Array
     */
    // Gets an Array of Weeks in the format of Week Str From The Month
    function get_weeks_from_month_year_arr($month_str)
    {
        $tmp_split = explode(" ", $month_str);
        $month_chosen = $tmp_split[0];
        $year_chosen = $tmp_split[1];

        $first_dt = new DateTime("$year_chosen-$month_chosen-1");
        $last_dt = new DateTime("$year_chosen-$month_chosen-1");
        $last_dt->modify('last day of this month');

        $first_week_of_month = (int) date('W', strtotime($first_dt->format('Y-m-d')));
        $last_week_of_month = date('W', strtotime($last_dt->format('Y-m-d')));

        $arr = [];
        for ($i = $first_week_of_month; $i <= $last_week_of_month; $i++)
            array_push($arr, "$year_chosen-$i");
        return $arr;
    }

    /**
     * get_product_arr_count_sales
     *
     * @param  Array $order_arr
     * @return Array
     */
    // Combine Part 3, 4, 5 into a single array
    function get_product_arr_count_sales($order_arr)
    {
        // 1. Number of Orders, 2. Number of Products, 3. Total Sales Revenue of Product
        $product_arr = $this->get_product_arr($order_arr);
        $product_count = (sizeof($product_arr) == 0) ? 0 : $this->get_product_count($product_arr);
        $total_sales_revenue = (sizeof($product_arr) == 0) ? 0 : $this->get_product_sales_revenue($product_arr);
        $product_arr = (sizeof($product_arr) > $this->num_of_items) ? array_slice($product_arr, 0, $this->num_of_items) : $product_arr;
        return array(sizeof($order_arr), $product_count, $total_sales_revenue, $product_arr);
    }

    /**
     * get_month_option_str
     *
     * @param  Array $month_option_arr
     * @return String
     */
    // Outputs String format of Month Options => For Javascript to Parse Input
    function get_month_option_str($option_arr){
        $arr = [];
        foreach($option_arr as $month_str){
            $tmp_split = explode(" ", $month_str);
            $month = $tmp_split[0];
            $year = $tmp_split[1];
            if(array_key_exists($year, $arr))
                array_push($arr[$year], $month);
            else
                $arr[$year] = array($month);
        }

        $year_arr = array_keys($arr);

        $str = "";
        foreach($year_arr as $year){
            $str .= $year . "/" . implode(" ", $arr[$year]) . ",";
        }

        return substr($str, 0, -1);
    }

    // Pipeline
    // 1. Get Options Based on...
    /**
     * @param  Array $order_arr
     * @return Array
     */

    // Date
    function get_date_options($order_arr)
    {
        $arr = array_map(function ($obj) {
            return date('Y-m-d', strtotime($obj->created_at));
        }, $order_arr);
        return array_unique($arr);
    }

    // Week
    function get_week_options($order_arr)
    {
        $arr = array_map(function ($obj) {
            return date('Y', strtotime($obj->created_at)) . "-" . date('W', strtotime($obj->created_at));
        }, $order_arr);
        return array_unique($arr);
    }

    // Month
    function get_month_options($order_arr)
    {
        $arr = array_map(function ($obj) {
            return date('M', strtotime($obj->created_at)) . " " . date('Y', strtotime($obj->created_at));
        }, $order_arr);
        return array_unique($arr);
    }

    // 2. Get Order Array Based on...
    /**
     * get_order_arr_[Conditions]
     * @param  Array $order_arr
     * @param  String $str
     * @return Array
     */

    // Time
    function get_order_arr_time($order_arr, $time_range)
    {
        $arr = array_filter($order_arr, function ($obj) use ($time_range) {
            $time_match = $this->check_time_group($obj->created_at);
            return $time_match == $time_range;
        });
        return $arr;
    }

    // Date
    function get_order_arr_date($order_arr, $daily_date)
    {
        $arr = array_filter($order_arr, function ($obj) use ($daily_date) {
            $date_match = date('Y-m-d', strtotime($obj->created_at));
            return $daily_date == $date_match;
        });
        return $arr;
    }

    // Week
    function get_order_arr_week($order_arr, $week_str)
    {
        $tmp_split = explode("-", $week_str);
        $year_chosen = $tmp_split[0];
        $week_chosen = $tmp_split[1];

        $arr = array_filter($order_arr, function ($obj) use ($year_chosen, $week_chosen) {
            $year_match = date('Y', strtotime($obj->created_at));
            $week_match = date('W', strtotime($obj->created_at));
            return $year_match == $year_chosen && $week_match == $week_chosen;
        });
        return $arr;
    }

    // Month
    function get_order_arr_month($order_arr, $month_str)
    {
        $tmp_split = explode(" ", $month_str);
        $month_chosen = $tmp_split[0];
        $year_chosen = $tmp_split[1];

        $arr = array_filter($order_arr, function ($obj) use ($month_chosen, $year_chosen) {
            $month_match = date('M', strtotime($obj->created_at));
            $year_match = date('Y', strtotime($obj->created_at));
            return $month_match == $month_chosen && $year_match == $year_chosen;
        });

        return $arr;
    }
    // 3. Get Product Array
    // Get Products Array From Order Array
    /**
     * get_product_arr
     *
     * @param  Array $order_arr
     * @return Array $product_arr
     */
    function get_product_arr($order_arr)
    {
        $arr = [];
        foreach ($order_arr as $order) {
            $tmp_arr = DB::select("SELECT * FROM order_items WHERE order_id = " . $order->id);
            $arr = array_merge($arr, $tmp_arr);
        }

        //Remove Duplicates
        $arr = $this->add_duplicates_tgt($arr);

        //Sort By Quantity
        usort($arr, function ($a, $b) {
            return $a->quantity <= $b->quantity;
        });

        return $arr;
    }

    // 4. Get Number of Products
    /**
     * get_product_count
     *
     * @param  Array $product_arr
     * @return Integer $number_of_products
     */
    function get_product_count($product_arr)
    {
        $num = array_reduce($product_arr, function ($i, $obj) {
            return $i += $obj->quantity;
        });
        return $num;
    }

    // 5. Get Total Amount of Sales Revenue / Canceled / Refunded Orders
    /**
     * get_product_sales_revenue
     *
     * @param  Array $product_arr
     * @return Integer $Total_Amount_Of_Sales_Revenue
     */
    function get_product_sales_revenue($product_arr)
    {
        $sum = array_reduce($product_arr, function ($i, $obj) {
            return $i += $obj->quantity * $obj->price;
        });
        return $sum;
    }

    // 6. Show Graph
    /**
     * get_graph_arr
     *
     * @param  Array $hashmap
     * @return Array
     */
    // Converts into Data Points to be parsed By Javascript
    function get_graph_arr($hashmap)
    {
        $str = "";
        $str2 = "";
        $str3 = "";
        $graph_arr = $hashmap;
        $label_arr = array_keys($graph_arr);

        foreach ($label_arr as $label) {
            $str .= $label . "/" . $graph_arr[$label][2] . "/";
            $str2 .= $label . "/" . $graph_arr[$label][1] . "/";
            $str3 .= $label . "/" . $graph_arr[$label][0] . "/";
        }

        // Str = Number of Orders, Str2 = Number of Products, Str3 = Total Amount of Sales Revenue
        $str = substr($str, 0, strlen($str) - 1);
        $str2 = substr($str2, 0, strlen($str2) - 1);
        $str3 = substr($str3, 0, strlen($str3) - 1);

        return array($str, $str2, $str3);
    }

    // 7. Get Data Points
    /**
     * get_time_graph_hashmap
     *
     * @param  String $daily_date_arr
     * @return Array
     */
    function get_time_graph_hashmap($daily_date_arr) {
        // All Time Range For the Option Array
        $options_arr = array("0:01 - 6:00", "6:01 - 12:00", "12:01 - 18:00", "18:01 - 0:00");

        $hashmap = [];
        foreach($options_arr as $option){
            $order_arr = $this->get_order_arr_time($daily_date_arr, $option);
            $hashmap[$option] = $this->get_product_arr_count_sales($order_arr);
        }
        return $hashmap;
    }

    // Daily
    function get_week_graph_hashmap($week_arr, $week_str){
        // All Dates For the Option Array
        $options_arr = $this->get_dates_from_week_year_arr($week_str);

        $hashmap = [];
        foreach($options_arr as $option){
            $order_arr = $this->get_order_arr_date($week_arr, $option);
            $hashmap[$option] = $this->get_product_arr_count_sales($order_arr);
        }
        return $hashmap;
    }

    // Weekly
    function get_month_graph_hashmap($month_arr, $month_str) {
        // All Weeks For the Option Array
        $options_arr = $this->get_weeks_from_month_year_arr($month_str);

        $hashmap = [];
        $ind = 1;
        foreach($options_arr as $option){
            $order_arr = $this->get_order_arr_week($month_arr, $option);
            $hashmap["Week " . $ind] = $this->get_product_arr_count_sales($order_arr);
            $ind++;
        }
        return $hashmap;
    }
};
