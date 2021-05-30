<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'access:status_check']);
    }

    public function index(Order $order){
        return view('order.item.index', compact('order'));
    }

    public function store(Order $order){

    }

    public function create(Order $order){

    }

    public function update(Order $order, Item $item){

    }

    public function destroy(Order $order, Item $item){

    }

}
