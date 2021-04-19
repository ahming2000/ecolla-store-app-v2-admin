<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * OrdersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $orders = Order::orderBy('created_at', 'desc')->paginate(5);
        return view('order.index', compact('orders'));
    }
}
