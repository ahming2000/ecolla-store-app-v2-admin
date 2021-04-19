<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Order $order){
        return view('order.item.index', compact('order'));
    }
}
